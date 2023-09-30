document.addEventListener('DOMContentLoaded', () => {

    
    /* popup
    ====================================*/
    $('.js-btn').on('click', function(e){
        e.preventDefault();
        var id = $(this).attr('data-link');
        $('#' + id).fadeIn(500);
        //        $('#' + id).fadeIn(500);
        //$('body').addClass('hidden-scroll');
        $('.js-overlay').fadeIn(500);
        return false;

    });

    $(".js-overlay").on("click", function() {
       // $('body').removeClass('hidden-scroll');
        $(".js-overlay").fadeOut(500);
        $('.js-popup').fadeOut(500);
    });

    $('.js-close').on('click', function() {
        $(".js-overlay").fadeOut();
        $('.js-popup').fadeOut(500);
    });
    
    

 

});




