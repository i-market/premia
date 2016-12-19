$(document).ready(function () {
  // модалка
  $('.modal').click(function (event) {
    if ($(event.target).closest(".modal>.block").length)
      return;
    $(".modal>.block, .modal").fadeOut(100);
    $('html, body').css({
      overflow: 'auto'
    });
    event.stopPropagation();
  });
  $('.modal .close').click(function () {
    $('.modal, .modal>.block').fadeOut(100);
    $('html, body').css({
      overflow: 'auto'
    });
  });
  $('[data-modal]').on('click', function () {
    var dataModal = $(this).attr('data-modal'),
      dataId = $('#' + dataModal);
    dataId.fadeIn(100);
    $(dataId).find('.block').fadeIn(100);
    $('html, body').css({
      overflow: 'hidden'
    });
  });

  // выбрать язык
  $('.lang').on('click', function () {
    $(this).toggleClass('open');
    $('.dd_choose_lang').slideToggle(150);
  });

  // гамбургер
  $('.hamburger').on('click', function () {
    $('.hamburger, .hidden_menu').toggleClass('open');
  });

  // плавность прокрутки по точкам
  $('a[href*=#]').bind("click", function (e) {
    var anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: $(anchor.attr('href')).offset().top
    }, 1000);
    e.preventDefault();
  });

  // скролл футера
  $(window).scroll(function () {
    if ($(window).scrollTop() > ($(document).height() - $(window).height() - 50)) {
      !$('.bottom_line').hasClass('scroll') ? $('.bottom_line').addClass('scroll') : null;
    } else {
      $('.bottom_line').removeClass('scroll');
    }
  });

  // якоря
  $("[data-href]").on("click", function (t) {
    var anchor = $(this).data('href');
    $("html, body").stop().animate({
      scrollTop: $("[data-anchor=" + anchor + "]").offset().top
    }, 700);
    t.preventDefault();
  });

  // скролл шапки
  var resize = function () {
    $(window).on('scroll', function () {
      var scroll = $(this).scrollTop();
      if (scroll > 250 && $(window).width() >= 1279) {
        $('.header, .main_menu, .content').addClass('scroll').show();
      } else {
        $('.header, .main_menu, .content').removeClass('scroll');
      }
    });
  };
  $(window).on('load resize', function () {
    resize();
  });

  // слайдер
  $('.slider').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    prevArrow: $('.wrap_slider .prev'),
    nextArrow: $('.wrap_slider .next'),
    autoplay: true,
    autoplaySpeed: 5000,
    responsive: [
      {
        breakpoint: 1280,
        settings: {
          appendDots: $('.wrap_slider .dots'),
          dots: true,
          arrows: false,
        }
    }
  ]
  });
  // слайдер схемы

  $('[data-modal]').on('click', function () {
    $('.slider_rent').slick('setPosition');
  });

  $('.modal_rent').each(function () {
    var all = $(this).find('.slider_rent .slide').length;
    $(this).find('.numbers .all').html(all);
  });

  $('.slider_rent').each(function () {
    $(this).on('afterChange', function (event, slick, currentSlide, nextSlide) {
      $(this).parents('.modal_rent').find('.current').html(currentSlide + 1);
    });
  });

  $('#rental_offers .slider_rent').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    prevArrow: $('#rental_offers .prev'),
    nextArrow: $('#rental_offers .next'),
  });
  $('#rental_offers_2 .slider_rent').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    prevArrow: $('#rental_offers_2 .prev'),
    nextArrow: $('#rental_offers_2 .next'),
  });
  $('#rental_offers_3 .slider_rent').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    prevArrow: $('#rental_offers_3 .prev'),
    nextArrow: $('#rental_offers_3 .next'),
  });

});