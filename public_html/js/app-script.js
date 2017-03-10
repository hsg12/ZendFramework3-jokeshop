$(function(){

/////////   For button back to top   ////////////////////////////////////////////////////

    $(window).scroll(function () {
        if ($(this).scrollTop() > 500) {
            $('#back-to-top').fadeIn();
        } else {
            $('#back-to-top').fadeOut();
        }
    });

    // scroll body to 0px on click
    $('#back-to-top').click(function () {
        $('#back-to-top').tooltip('hide');
        $('body,html').animate({
            scrollTop: 0
        }, 300);
        return false;
    });

    $('#back-to-top').tooltip('show');


/////////   Settings   //////////////////////////////////////////////////////////////////

    if ($(document).width() < 430) {
        $('.welcome').css('marginTop', 0);
    }

    if ($(document).width() < 768) {
        $('.need-help').css('paddingLeft', '15px');
        $('.carousel-caption').css('padding', 0);
        $('.carousel-caption h5').css('margin', 0);
    }

    if ($(document).width() < 992) {
        $('.items-panel .panel-footer span:first-child + span').removeClass('pull-right').css('display', 'block');
        $('.items-panel .panel-footer').addClass('text-center');
    }

    if ($(document).width() < 340) {
        $('div.items-panel').removeClass('col-xs-6').addClass('col-xs-12');
    }

    if ($(document).width() > 767) {
        $('.footer .soc-icons').addClass('pull-right');
    } else {
        $('.footer .soc-icons').addClass('copyright');
    }

/////////   menu vert   ///////////////////////////////////////////////////////

    if ($(document).width() < 768) {
        $('ul.menu_vert').removeClass('menu_vert').addClass('topnav');
    } else {
        $('.menu_vert').liMenuVert({
            delayShow:300,		//Задержка перед появлением выпадающего меню (ms)
            delayHide:300	    //Задержка перед исчезанием выпадающего меню (ms)
        });
    }

    //  In order did not work parent element a  //

    $('ul.menu_vert li a').on('click', function(){
        if ($(this).parent('li').has('ul').length != 0) {
            return false;
        }
    });

/////////   menu accordion   ////////////////////////////////////////////////////////////

    $(document).ready(function() {
        $(".topnav").accordion({
            accordion:true,
            speed: 500,
            closedSign: '<span class="caret"></span>',
            openedSign: '<span class="dropup"><span class="caret"></span></span>'
        });
    });

    // In order don't work link witch has children

    $('ul.topnav li a').on('click', function(){
        if ($(this).parent('li').has('ul').length != 0) {
            return false;
        }
    });

/////////   For auth forms   ////////////////////////////////////////////////////////////

    $('#login-form label:eq(2)').removeClass('control-label').addClass('text-right');

    if ($(document).width() > 767) {
        $('#form-register div.col-sm-3').addClass('text-right');
    }
});



