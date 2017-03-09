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
  $('.label_textarea').each(function(idx, itm) {
    var $t = $(this).find('textarea');
    var t = $t[0],
      d = itm;
    t.addEventListener('keydown', function () {
      setTimeout(function () {
        t.style.cssText = 'height:0px';
        var height = t.scrollHeight + parseFloat($t.css('border-bottom-width'));
        d.style.cssText = 'height:' + height + 'px';
        t.style.cssText = 'height:' + height + 'px';
      }, 0);
    });
  });
  // табы
  $(function () {
    $('[data-tabLinks]').on('click', function () {
      var targetNode = $('[data-tabContent=' + $(this).attr('data-tabLinks') + ']');
      $(this).parent().find('[data-tabLinks]').removeClass('active').filter(this).addClass('active');
      targetNode.parent().find('> [data-tabContent]').hide().filter(targetNode).show();
    });
    $('[data-tabLinks]').parent('.activate').find('[data-tabLinks]:nth-child(1)').trigger('click');
  });

});