import './bootstrap';
$(document).ready(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
    //axios.post('causes')
    //.then(function(response){
    //    console.log(response.data); // ex.: { user: 'Your User'}
    //    console.log(response.status); // ex.: 200
    //}); 
    $('.fa-trash-alt').click(function(e){
        e.preventDefault();
        var data = $(e.currentTarget).data();
        console.log(window.location.pathname);
        axios.delete(window.location.pathname + '/' + data.id)
        .then((response) => {
            $(e.currentTarget).closest('tr').remove(); 
            console.log(response)
        }, (error) => {
            console.log(error);
        })
        
    });

    $('form').submit(function(e){
        var data = $(e.currentTarget).serialize();
        axios.post('categories', data)
        .then(function(response){
            console.log(response.data); // ex.: { user: 'Your User'}
            console.log(response.status); // ex.: 200
        });
    });
});