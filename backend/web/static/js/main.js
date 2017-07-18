$(document).ready(function(){
    var preloader = function(size){
        var classes = 'fa fa-spinner fa-spin fa-fw';

        if(size !== undefined){
            classes += ' fa-' + size + 'x';
        }

        return '<div class="text-center"><i class="' + classes + '"></i><span class="sr-only">Loading...</span></div>';
    };

    $(document).on('click', 'button.editTrack', function(){
        var id = $(this).closest('[data-key]').data('key'),
            modal = $('#trackEditModal');

        $(modal).modal('show');

        $.ajax({
            url: routes.tracks.edit + '?id=' + id
        }).success(function(data){
            $(modal).find('.modal-body div').html(data);
        })
    }).on('show.bs.modal', '.modal', function(){
        if($(this).data('static')){
            return;
        }

        var modal = $(this).find('.modal-body [data-pjax-container]');

        if(modal === undefined){
            modal = $(this).find('.modal-body');
        }

        modal.html(preloader(3));
    }).on('click', '[data-pjax-container] form button[type="submit"]', function(){
        $(this).prop('disabled', true);
        $(this).html(preloader());
    });
});