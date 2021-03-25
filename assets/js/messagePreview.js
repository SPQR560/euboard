$(document).ready(function() {
    $('.board-message').hover(function(e){
        e.preventDefault();
        let id = $(this).attr("href");
        let message = $(id).html();
        let parentElement = $(this).parent()

        $(parentElement).children('.pop-up')
            .addClass('border border-info pl-3')
            .html(message)
            .show();
    }, function () {
        let childrenPopUp = $(this).parent().children('.pop-up');
        childrenPopUp
            .removeClass('border border-info pl-3')
            .hide();
    })
});