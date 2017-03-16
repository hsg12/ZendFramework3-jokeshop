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
        $('.carousel-caption').css('padding', 0);
        $('.carousel-caption h5').css({
            margin: 0,
            fontSize: '14px'
        });
        $('.carousel-caption div').css('fontSize', '12px');
        $('.admin-product-form').css('paddingLeft', '15px');
        $('.admin-add-product-block').removeClass('pull-right');
        $('.admin-product-edit-img').css('width', '50%');
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

/////////   For user delete modal   /////////////////////////////////////////////////////

    $('#delete-product').click('on', function(){
        $('.delete-product-form').submit();
    });

/////////






});



