import './bootstrap';
$(document).ready(function () {


    /**
     * Toggle the left side menu
     */
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });

    /**
     * Generic ajax delete function; let the backend do the checkin'
     */
    $('.fa-trash-alt').click(function (e) {
        e.preventDefault();
        var data = $(e.currentTarget).data();
        console.log(window.location.pathname);
        axios.delete(window.location.pathname + '/' + data.id)
            .then(() => {
                $(e.currentTarget).closest('tr').remove();
            }, (error) => {
                // todo: Proper error handling
                console.log(error);
            })
    });

    /**
     * Generic ajax submit function; let the backend do the checkin'
     */
    $('form').submit(function (e) {
        var form = $(e.currentTarget);
        var dataholder = $(e.currentTarget).find('.form-dataholder');
        var routedata = dataholder.data();

        // Use the window location if no path is given
        if (!routedata) {
            routedata = {
                routePath: window.location.pathname
            };
        }

        // Add a parent id if needed and given
        var formdata = $(e.currentTarget).serialize();

        if (routedata.routeId) {
            formdata += "&id=" + encodeURIComponent(routedata.routeId);
        }
        axios.post(routedata.routePath, formdata)
            .then(function (response) {
                // todo: Process success
                form.trigger("reset");
                form.closest('.modal').modal('hide');

            }, (error) => {
                // todo: Proper error handling
                console.log(error);
            })
    });

    $('.form-modal').on('show.bs.modal', function (e) {
        var data = $(e.relatedTarget).data();
        console.log(data.id);
        var dataholder = $(this).find('.form-dataholder');
        dataholder.data('route-id', data.id);
        dataholder.data('route-path', 'mitigations');
    })

});
