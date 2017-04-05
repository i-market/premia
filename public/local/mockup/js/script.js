$(document).ready(function () {
  function adjustTextareaHeight($textarea, $label) {
    $textarea.css('height', 0);
    var height = $textarea[0].scrollHeight + parseFloat($textarea.css('border-bottom-width'));
    $label.css('min-height', height);
    $textarea.css('height', height);
  }

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
  $('.modal .close, .modal [data-close]').click(function () {
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
    $(dataId).find('.label_textarea').each(function() {
      var $label = $(this);
      var $textarea = $label.find('textarea');
      adjustTextareaHeight($textarea, $label);
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

  $('.label_textarea').each(function() {
    var $label = $(this);
    var $textarea = $label.find('textarea');
    adjustTextareaHeight($textarea, $label);
    $textarea.on('change input', function() {
      setTimeout(function() {
        adjustTextareaHeight($textarea, $label);
      }, 0);
    });
  });

  function scrollTo($target, whitespace) {
    whitespace = whitespace || 40;
    var headerHeight = $('header').filter(function() {
      return $(this).css('position') === 'fixed';
    }).height();
    if (headerHeight !== null) {
      var offset = headerHeight === null ? 0 : headerHeight + whitespace;
      var $page = $('html, body');
      var scrollEvents = 'scroll mousedown wheel DOMMouseScroll mousewheel keyup touchmove';
      $page.on(scrollEvents, function() {
        $page.stop();
      });
      $page.stop().animate({
        scrollTop: $target.offset().top - offset
      }, 1000, function() {
        $page.off(scrollEvents);
      });
    }
  }

  $(function () {
    // табы
    function activate($el) {
      var targetNode = $('[data-tabContent=' + $el.attr('data-tabLinks') + ']');
      $el.parent().find('[data-tabLinks]').removeClass('active').filter($el).addClass('active');
      targetNode.parent().find('> [data-tabContent]').hide().filter(targetNode).show();
      targetNode.find('.label_textarea').each(function() {
        var $label = $(this);
        var $textarea = $label.find('textarea');
        adjustTextareaHeight($textarea, $label);
      });
    }
    // gallery
    $('.gallery').each(function() {
      var $gallery = $(this);
      $gallery.find('.album').on('click', function() {
        scrollTo($gallery.find('.gallery-slider.active'), 20);
      });
    });
    $('[data-tabLinks]').on('click', function() {
      activate($(this));
      if ($(this).attr('data-scroll-to') === 'true') {
        var $target = $('[data-tabContent=' + $(this).attr('data-tabLinks') + ']');
        scrollTo($target);
      }
    });
  });

});