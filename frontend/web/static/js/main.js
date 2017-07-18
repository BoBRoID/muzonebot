$(document).ready(function(){
    var pleer = undefined,
        icon = function(name){
            return '<i class="fa fa-' + name + '"></i>';
        },
        preloader = function(){
            return '<div class="text-center"><i class="fa fa-spinner fa-spin fa-fw"></i></div>';
        },
        createPlayer = function(id){
            var wavesurfer = WaveSurfer.create({
                container: '[data-key="' + id + '"] .waveform',
                height: 40,
                normalize: true,
                hideScrollbar: true
            });

            wavesurfer.on('ready', function () {
                wavesurfer.play();
                $(wavesurfer.container).toggleClass('pt-3');
            });

            wavesurfer.on('pause', function(){
                $(wavesurfer.container).closest('[data-key]')
                    .find('button.pauseTrack')
                    .html(icon('play'))
                    .addClass('listenTrack')
                    .removeClass('pauseTrack');
            });

            wavesurfer.on('play', function(){
                $(wavesurfer.container).closest('[data-key]')
                    .find('button.listenTrack')
                    .html(icon('pause'))
                    .prop('disabled', false)
                    .addClass('pauseTrack')
                    .removeClass('listenTrack');
            });

            wavesurfer.on('loading', function(percents, object){
                console.log('loading');
                console.log(percents);
                console.log(object);

                $(wavesurfer.container).closest('[data-key]')
                    .find('button.listenTrack')
                    .html(icon('pause'))
                    .prop('disabled', false)
                    .addClass('pauseTrack')
                    .removeClass('listenTrack');
            });

            wavesurfer.on('finish', function(){
                var nextId = null,
                    item = undefined;

                while(nextId === null){
                    item = item !== undefined ? item.next() : $(wavesurfer.container).closest('[data-key]').next();

                    if(item !== undefined && item.find('button[disabled]') === 0){
                        nextId = $(item).data('key');
                        break;
                    }
                }

                pleer.destroy();
                createPlayer(nextId);

                $(pleer.container).closest('[data-key]')
                    .find('button.listenTrack')
                    .html(preloader())
                    .prop('disabled', true);
            });

            wavesurfer.load(routes.tracks.get + '?id=' + id);

            pleer = wavesurfer;
        };

    $(document).on('click', '.toggleTrack', function(){
        var song_id = $(this).closest('[data-key]').data('key'),
            button = $(this);

        $.ajax({
            url: routes.tracks.toggle,
            method: 'post',
            data: {
                trackId: song_id
            }
        }).success(function(data){
            if(data.result === 'success'){
                var btnClass = 'fa-heart' + (data.state === 'added' ? '' : '-o');

                $(button)
                    .attr('title', data.message)
                    .html('<i class="fa ' + btnClass+ '"></i>');
            }
        });
    }).on('click', 'button.editTrack', function(){
        var id = $(this).closest('[data-key]').data('key');

        $('#trackEditModal').modal('show');

        $.ajax({
            url: routes.tracks.edit + '?id=' + id
        }).success(function(data){
            $('#trackEditModal .modal-body div').html(data);
        })
    }).on('show.bs.modal', '.modal', function(){
        if($(this).data('static')){
            return;
        }

        var modal = $(this).find('.modal-body');

        if(modal === undefined){
            modal = $(this).find('.modal-body');
        }

        modal.html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>');
    }).on('submit', '.modal [data-pjax-container] form', function(){
        console.log($(this));
    }).on('click', '.listenTrack', function(){
        var id = $(this).closest('[data-key]').attr('data-key'),
            currentTrack = pleer!== undefined ? $(pleer.container).closest('[data-key]') : undefined;

        if(currentTrack !== undefined && $(currentTrack).attr('data-key') === id){
            pleer.playPause();
        }else{
            if(pleer !== undefined){
                $(currentTrack)
                    .find('.waveform')
                    .toggleClass('pt-3');

                pleer.pause();
                pleer.destroy();
            }

            createPlayer(id);
        }
    }).on('click', '.pauseTrack', function(){
        pleer.playPause();
    });
});