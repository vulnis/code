import './bootstrap';
import moment from 'moment';
import flatpickr from 'flatpickr';
$(document).ready(function () {

    var locale = document.getElementsByTagName('html')[0].getAttribute('lang') || window.navigator.userLanguage || window.navigator.language;
    /**
     * Toggle the left side menu
     */
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
    flatpickr('.flatpickr', {
        minDate: 'today',
        locale: locale

    });
    /**
     * Generic ajax delete function; let the backend do the checkin'
     */
    $('.fa-trash-alt').click(function (e) {
        e.preventDefault();
        var data = $(e.currentTarget).data();
        var route = window.location.pathname;
        if(data.route){
            route = data.route;
        }
        axios.delete(route + '/' + data.id)
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
        console.log(routedata.routePath);
        axios.post(routedata.routePath, formdata)
            .then(function (response) {
                var model = routedata.routePath.replace(/\//g, "");
                if(model == "mitigations"){
                    var mitigation = response.data.created;
                    //Add the response to the correct table
                    var tbody = $('.' + model).find('tbody');
                    if(tbody){
                        var icon = mitigation.type === 'CA' ? "fas fa-highlighter fa-fw" : "fas fa-shield-alt fa-fw";
                        tbody
                            .append('<tr>')
                                .append([
                                    '<td><i class="' + icon + '"></i></td>',
                                    '<td>' + mitigation.current_solution + '</td>'
                                ]);
                    }
                }
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
