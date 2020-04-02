$(document).ready(function(){
    // $('.nav-button').click(function(){
    //   $('.nav-button').toggleClass('change');
    // });
  
    $(window).scroll(function(){
      var position = $(this).scrollTop();
      console.log(position)
      if(position >= 400) {
        $('.nav-menu').addClass('navbar-reduct');
      } else if(position < 200) {
        $('.nav-menu').removeClass('navbar-reduct');
      }
    });
  });

  //https://codepen.io/Masoudm/pen/mgdPVP
  //https://www.youtube.com/watch?v=M_sZvTGnU0w