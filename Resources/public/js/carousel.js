// $("#carouselAccueil").ready(function(){
//     // var height = $('.carousel-item').height();
//     // $('.carousel-item img').css('height',$('.carousel-item').height()+"px");
//     // alert($('.carousel-item').html());
//     // alert($('.carousel-item').height());
//     // var minHeight = "200px";
//     // $(".carousel .carousel-item").each(
//         height = $('.carousel-item').height();
//         alert($('.carousel-item').height());
//         alert(height);
//     // )
// });

// Set all carousel items to the same height
function carouselNormalization() {

    window.heights = [], //create empty array to store height values
    window.tallest; //create variable to make note of the tallest slide

    function normalizeHeights() {
        jQuery('#carousel .carousel-item').each(function() { //add heights to array
            window.heights.push(jQuery(this).outerHeight());
            //alert("size "+jQuery(this).outerHeight());
        });
        window.tallest = Math.min.apply(null, window.heights); //cache largest value
        //alert("min "+window.tallest);
        jQuery('#carousel .carousel-item').each(function() {
            jQuery(this).css('height',tallest + 'px');
        });
    }
    normalizeHeights();

    jQuery(window).on('resize orientationchange', function () {

        window.tallest = 0, window.heights.length = 0; //reset vars
        jQuery('.carousel .carousel-item').each(function() {
            jQuery(this).css('height','0'); //reset min-height
        }); 

        normalizeHeights(); //run it again 

    });

}

$("#carousel").ready(function(){
//jQuery( document ).ready(function() {
    carouselNormalization();
});