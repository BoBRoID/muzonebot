$(document).ready(function(){
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
    });
});