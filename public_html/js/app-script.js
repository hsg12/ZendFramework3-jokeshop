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



    if ($(document).width() < 768) {
        $('.carousel-caption').css('padding', 0);
        $('.carousel-caption h5').css({
            margin: 0,
            fontSize: '14px'
        });
        $('.carousel-caption div').css('fontSize', '12px');
        $('.admin-product-form').css('paddingLeft', '15px');
        $('.admin-add-product-block').removeClass('pull-right');
        $('.admin-product-edit-img').css('width', '50%');
        $('.basket-cancel-button').removeClass('pull-right').css('marginTop', '10px');
        $('.my-order-bottom-panel img').css({
            width: '40%',
            height: '40%',
            marginBottom: '20px',
        });
        $('#mainPageSearchResult').css({
            left: '14px',
            top: '290px'
        });
    }

    if ($(document).width() < 992) {
        $('.items-panel .panel-footer span:first-child + span').removeClass('pull-right').css('display', 'block');
        $('.items-panel .panel-footer').addClass('text-center');
        $('strong#sign-in-obligatory').css({
            left: '1px',
            bottom: '0px',
            width: '100%',
            textAlign: 'center'
        });
        $('strong#sign-in-obligatory.product-sign-in-obligatory').css({
            left: '2px',
            bottom: '-36px',
            width: '100%',
            textAlign: 'center'
        });
    }

    if ($(document).width() < 282) {
        $('strong#sign-in-obligatory.product-sign-in-obligatory').css({
            bottom: '-60px',
        });
    }

    if ($(document).width() < 430) {
        $('.welcome').css('marginTop', 0);
        $('.carousel-caption h5').css('fontSize', '9px');
        $('.carousel-caption div').css('fontSize', '9px');
    }

    if ($(document).width() < 1200) {
        $('strong#sign-in-obligatory.product-sign-in-obligatory').css({
            right: '35px'
        });
    }

    if ($(document).width() < 340) {
        $('div.items-panel').removeClass('col-xs-6').addClass('col-xs-12');
    }

    if ($(document).width() > 767) {
        $('.footer .soc-icons').addClass('pull-right');
    } else {
        $('.footer .soc-icons').addClass('copyright');
        $('<br>').insertBefore($('.title-render'));
        $('.nav.navbar-nav').css('marginBottom', 0);
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

/////////   For form asterisk   /////////////////////////////////////////////////////////

    var asterisk = function () {
        var obj = $("form label:contains('*')");

        if (obj) {
            var objLength = obj.length - 1;
            for (var i = objLength; i >= 0; i--) {
                var newString = obj.eq(i).html().replace("*", "<strong class='color-red'>*</strong>");
                obj.eq(i).html(newString);
            }
        }
    }();

/////////   For button input file   /////////////////////////////////////////////////////

    $(":file").jfilestyle({inputSize: "50%"});

/////////   Product search for admin panel   ////////////////////////////////////////////

    var adminProductSearch = function () {
        var formData = $('#adminProductSearch').serialize();

        $.ajax({
            url: '/admin/products/search',
            type: 'post',
            dataType: 'json',
            data: formData,
            success: function(data){
                if (data) {
                    $('#adminProductSearchResult li').remove();
                    for (key in data) {
                        var dataAppend = '<li><a href="/admin/products/edit/' + data[key].id + '">' + data[key].name + '</a></li>';
                        $('#adminProductSearchResult').append(dataAppend);
                    }
                } else {
                    $('#adminProductSearchResult li').remove();
                    $('#adminProductSearchResult').append('<li>Nothing found</li>');
                }
            },
        });

        return false;
    }

    $('#adminProductSearch').on('submit', adminProductSearch);
    $(document).click(function(){
        $('#adminProductSearchResult li').remove();
    });

/////////   For product delete modal   //////////////////////////////////////////////////

    $('#delete-product').click('on', function(){
        $('.delete-product-form').submit();
    });

/////////   For user delete modal   /////////////////////////////////////////////////////

    $('#delete-user').click('on', function(){
        $('.delete-user-form').submit();
    });

/////////   For admin delete modal   /////////////////////////////////////////////////////

    $('#delete-admin').click('on', function(){
        $('.delete-admin-form').submit();
    });

/////////   Add to cart   ///////////////////////////////////////////////////////////////

    var signInMessage = function (obj, message) {
        obj.parent().next('#sign-in-obligatory').text(message).fadeIn(300);
        setTimeout(function() {
            obj.parent().next('#sign-in-obligatory').text(message).fadeOut(300);
        }, 3000);
    };

    var addToCart = function(){
        var obj = $(this);
        var identity = $('.add-to-cart').attr('data-identity');
        if (identity == 'false') {
            signInMessage(obj, 'Sign in for this action.');
            return false;
        }

        var id = $(this).attr('data-id');
        var obj = $(this);

        $.ajax({
            url: '/basket/add/' + id,
            type: 'post',
            dataType: 'json',
            success: function(data){
                if (data) {
                    $('.selected-product-total-count').text(data.countProducts);
                    $('.count-concrete-product').text(data.countConcreteProduct);
                    obj.parent().siblings('li').eq(0).children('span').text(data.countConcreteProduct);
                    $('.product-price').text((data.price * data.countConcreteProduct).toFixed(2));
                    obj.parent().parent().parent().siblings('.basket-price').children('span').text((data.price * data.countConcreteProduct).toFixed(2));
                    $('.total-price').text(data.totalPrice.toFixed(2));
                }
            }
        });

        return false;
    };

    $('.add-to-cart').on('click', addToCart);

/////////   Subtract from cart   ////////////////////////////////////////////////////////

    var subtractFromCart = function(){
        var id = $(this).attr('data-id');
        var obj = $(this);

        $.ajax({
            url: '/basket/subtract/' + id,
            type: 'post',
            dataType: 'json',
            success: function(data){
                if (data) {
                    $('.selected-product-total-count').text(data.countProducts);
                    obj.parent().prev().children('span.basket-concrete-product-count').text(data.countConcreteProduct);
                    obj.parent().parent().parent().siblings('.basket-price').children('span').text((data.price * data.countConcreteProduct).toFixed(2));
                    $('.total-price').text(data.totalPrice.toFixed(2));
                }
            }
        });

        return false;
    };

    $('.subtract-from-cart').on('click', subtractFromCart);

/////////   For confirm plugin   ////////////////////////////////////////////////////////

    $('.delete-concrete-product').jConfirmAction({
        question: 'Are you sure?',
        noText: 'Cancel'
    });

    $('.delete-concrete-product-admin').jConfirmAction({
        question: 'Are you sure?',
        noText: 'Cancel'
    });

    $('.delete-from-slider').jConfirmAction({
        question: 'Are you sure?',
        noText: 'Cancel'
    });

    $('.delete-category-admin').jConfirmAction({
        question: 'All nested products will be deleted. Are you sure?',
        noText: 'Cancel'
    });

/////////   For tooltip   ///////////////////////////////////////////////////////////////

    $('[data-toggle="tooltip"]').tooltip();

/////////   Search on main page   ///////////////////////////////////////////////////////

    var searchOnMainPage = function(){
        var search = $('.searchOnMainPage').serialize();

        $.ajax({
            url: '/home/search',
            type: 'post',
            dataType: 'json',
            data: search,
            success: function(data){
                if (data) {
                    $('#mainPageSearchResult li').remove();
                    for (var key in data) {
                        var append = '<li><a href="/product/' + data[key]['id'] + '">' + data[key]['name'] + '</a></li>'
                        $('#mainPageSearchResult').append(append);
                    }

                    if (($('#mainPageSearchResult').is(':visible')) === false) {
                        $('#mainPageSearchResult').slideToggle(300);
                    }
                } else {
                    $('#mainPageSearchResult li').remove();
                    $('#mainPageSearchResult').append('<li>Nothing found</li>');

                    if (($('#mainPageSearchResult').is(':visible')) === false) {
                        $('#mainPageSearchResult').slideToggle(300);
                    }
                }
            }
        });

        return false;
    };

    $('.searchOnMainPage').on('submit', searchOnMainPage);

    $(document).on('click', function(){
        $('#mainPageSearchResult').css('display', 'none');
    });

/////////   Settings for admin slider form   ////////////////////////////////////////////

    $('#slider input:eq(3)').removeClass('form-control');
    

/////////   For form radio box   ////////////////////////////////////////////////////////

    $('.radio-box label:eq(0)').append('&nbsp;&nbsp;&nbsp;');

/////////   For portfolio   /////////////////////////////////////////////////////////////

    if ($(document).width() < 768) {
        $('.portfolio').css('marginBottom', '20px');
    }

/////////   END   ///////////////////////////////////////////////////////////////////////
});



