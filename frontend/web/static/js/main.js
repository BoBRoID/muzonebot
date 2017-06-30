$(document).ready(function(){
    $(document).on('click', '.toggleTrack', function(){
        var song_id = $(this).closest('[data-key]').data('key'),
            button = $(this);

        $.ajax({
            method: 'POST',
            url: '/tracks/toggle-my',
            data: {
                song_id: song_id
            }
        }).success(function(data){
            if(data.result === 'success'){
                var btnClass = 'fa-heart' + (data.state === 'added' ? '' : '-o');

                $(button)
                    .attr('title', data.message)
                    .html('<i class="fa ' + btnClass+ '"></i>');
            }
        });
    });
});