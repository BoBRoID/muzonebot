$(document).ready(function(){
    var pleer = undefined,
        icon = function(name){
            return '<i class="fa fa-' + name + '"></i>';
        },
        preloader = function(size){
            var classes = 'fa fa-spinner fa-spin fa-fw';

            if(size !== undefined){
                classes += ' fa-' + size + 'x';
            }

            return '<div class="text-center"><i class="' + classes + '"></i><span class="sr-only">Loading...</span></div>';
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

            wavesurfer.on('loading', function(){
                $(wavesurfer.container).closest('[data-key]')
                    .find('button.listenTrack')
                    .html(icon('pause'))
                    .prop('disabled', false)
                    .addClass('pauseTrack')
                    .removeClass('listenTrack');
            });

            wavesurfer.on('finish', function(){
                $(pleer.container)
                    .toggleClass('pt-3');

                $(pleer.container).closest('[data-key]')
                    .find('button.pauseTrack')
                    .html(icon('play'))
                    .addClass('listenTrack')
                    .removeClass('pauseTrack');

                var nextId = null,
                    item = $(wavesurfer.container).closest('[data-key]');

                while(nextId === null){
                    item = item.next();

                    if(item === undefined){
                        break;
                    }else if(item.find('button[disabled]').length === 0){
                        nextId = $(item).data('key');
                        break;
                    }
                }

                pleer.destroy();
                setPreloaderToListenTrackButton(nextId);
                createPlayer(nextId);
            });

            wavesurfer.load(routes.tracks.get + '?id=' + id);

            pleer = wavesurfer;
        }, setPreloaderToListenTrackButton = function(id){
            $('[data-key="' + id + '"]')
                .find('button.listenTrack')
                .html(preloader())
                .prop('disabled', true);
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
                var btnClass = 'heart' + (data.state === 'added' ? '' : '-o');

                $(button)
                    .attr('title', data.message)
                    .html(icon(btnClass));
            }
        });
    }).on('click', 'button.editTrack', function(){
        var id = $(this).closest('[data-key]').data('key');

        $('#trackEditModal').modal('show');

        $.ajax({
            url: routes.tracks.edit + '?id=' + id
        }).success(function(data){
            $('#trackEditModal .modal-body [data-pjax-container]').html(data);
        })
    }).on('show.bs.modal', '.modal', function(){
        if($(this).data('static')){
            return;
        }

        var modal = $(this).find('.modal-body [data-pjax-container]');

        if(modal === undefined){
            modal = $(this).find('.modal-body');
        }

        modal.html(preloader());
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

            setPreloaderToListenTrackButton(id);
            createPlayer(id);
        }
    }).on('click', '.pauseTrack', function(){
        pleer.playPause();
    }).on('click', '[data-pjax-container] form button[type="submit"]', function(){
        $(this).prop('disabled', true);
        $(this).html(preloader());
    });
});