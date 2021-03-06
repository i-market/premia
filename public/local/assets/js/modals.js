// TODO rename to forms.js

import _ from 'lodash';

function toggleSubmitButton($form, state) {
  const $button = $form.find('button[type=submit], button.g-recaptcha');
  if (state) {
    $button.removeAttr('disabled');
  } else {
    $button.attr('disabled', 'disabled');
  }
}

function mutateField($label, errorMaybe) {
  $label.toggleClass('error', !_.isNull(errorMaybe));
  let $message = $label.find('.message');
  if (!$message.length) {
    $message = $('<span class="message"></span>').appendTo($label);
  }
  // clear
  $message.html(_.isNull(errorMaybe) ? '' : errorMaybe.message);
}

function mutateMessage($form, text, type = 'info') {
  $(`<div class="form-message ${type}">${text}</div>`)
    .replaceAll($form.find('.form-message'))
    .toggle(!_.isEmpty(text));
}

function mutateForm($form, response) {
  $form.find(':input[name]').each(function() {
    const $label = $(this).parent('label');
    const fieldName = $(this).attr('name');
    const errorMaybe = _.has(response.errors, fieldName)
      ? {message: _.join(response.errors[fieldName], '<br>')}
      : null;
    mutateField($label, errorMaybe);
  });
  if (_.isEmpty(_.get(response, 'bxMessage', {}))) {
    // clear
    mutateMessage($form, null);
  } else {
    const msgType = response.bxMessage.type === 'ERROR' ? 'error' : 'info';
    mutateMessage($form, response.bxMessage.message, msgType);
  }
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

export default {init, mutateForm, mutateMessage, toggleSubmitButton};