// TODO rename to forms.js

import _ from 'lodash';

function showMessage($form, text, type = 'success') {
  $form.find('.message').replaceWith(`<div class="message ${type}">${text}</div>`);
}

function showBxMessage($form, bxMsg) {
  const type = {
    'OK': 'success',
    'ERROR': 'error'
  };
  return showMessage($form, bxMsg.message, type[bxMsg.type]);
}

function mutateField($label, errorMaybe) {
  $label.toggleClass('error', !_.isNull(errorMaybe));
  let $message = $label.find('.message');
  if (!$message.length) {
    $message = $('<span class="message"></span>').appendTo($label);
  }
  $message.html(_.isNull(errorMaybe) ? '' : errorMaybe.message);
}

function errorMessageHtml(errors, bxMessage) {
  console.log({errors, bxMessage});
  return _.join(_.flatMap(errors, _.identity), '<br>');
}

function formFields($form) {
  return _.reduce($form.serializeArray(), (m, field) => {
    return _.set(m, field.name, field.value);
  }, {});
}

function init($form, onSuccess) {
  $form.on('submit', (event) => {
    event.preventDefault();
    $.post($form.attr('action'), formFields($form), onSuccess);
  });
}

export default {showMessage, showBxMessage, init, mutateField};