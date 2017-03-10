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
  $('[data-modal]').on('click', function (event) {
    if ($(this).is('a')) {
      event.preventDefault();
    }
    // in case we open a modal from another modal
    $('.modal').fadeOut(100);
    var dataModal = $(this).attr('data-modal'),
      dataId = $('#' + dataModal);
    dataId.fadeIn(100);
    $(dataId).find('.block').fadeIn(100);
    $('html, body').css({
      overflow: 'hidden'
    });
  });
  // гамбургер
  $('.hamburger').on('click', function () {
    $('.header, .footer, .content, body, html').toggleClass('open');
  });
  // textarea
  var maxLength = 15000;
  $('textarea').each(function () {
    $(this).keyup(function () {
      var length = $(this).val().length;
      length = maxLength - length;
      $(this).parent().find('.chars').text(length);
    });
    $(this).focus(function () {
      $(this).parent().addClass('focus');
      $(this).parent().find('.placeholder').css('color', '#0066cc');
    });
    $(this).focusout(function () {
      $(this).parent().find('.placeholder').css('color', '#606778');
      if ($(this).val() === '') {
        $(this).parent().removeClass('focus');
      } else {
        $(this).parent().addClass('focus');
      }
    });
  });

  function adjustTextareaHeight($t, d) {
    var t = $t[0];
    t.style.cssText = 'height:0px';
    var height = t.scrollHeight + parseFloat($t.css('border-bottom-width'));
    d.style.cssText = 'min-height:' + height + 'px';
    t.style.cssText = 'height:' + height + 'px';
  }

  $('.label_textarea').each(function(idx, itm) {
    var $t = $(this).find('textarea');
    var t = $t[0],
      d = itm;
    adjustTextareaHeight($t, d);
    t.addEventListener('keydown', function () {
      setTimeout(function() {
        adjustTextareaHeight($t, d);
      }, 0);
    });
  });
  // табы
  $(function () {
    function activate($el) {
      var targetNode = $('[data-tabContent=' + $el.attr('data-tabLinks') + ']');
      $el.parent().find('[data-tabLinks]').removeClass('active').filter($el).addClass('active');
      targetNode.parent().find('> [data-tabContent]').hide().filter(targetNode).show();
      targetNode.find('.label_textarea').each(function(idx, itm) {
        var $t = $(this).find('textarea');
        adjustTextareaHeight($t, itm);
      });
    }
    $('[data-tabLinks]').on('click', function () {
      activate($(this));
      if ($(this).attr('data-scroll-to') === 'true') {
        var targetNode = $('[data-tabContent=' + $(this).attr('data-tabLinks') + ']');
        // TODO extract function
        var headerHeight = $('header').filter(function() {
          return $(this).css('position') === 'fixed';
        }).height();
        if (headerHeight !== null) {
          var whitespace = 40;
          var offset = headerHeight === null ? 0 : headerHeight + whitespace;
          var $page = $('html, body');
          var scrollEvents = 'scroll mousedown wheel DOMMouseScroll mousewheel keyup touchmove';
          $page.on(scrollEvents, function() {
            $page.stop();
          });
          $page.stop().animate({
            scrollTop: targetNode.offset().top - offset
          }, 1000, function() {
            $page.off(scrollEvents);
          });
        }
      }
    });
    activate($('[data-tabLinks]').parent('.activate').find('[data-tabLinks]:nth-child(1)'));
  });

});