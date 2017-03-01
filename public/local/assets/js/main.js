import modals from './modals';
import _ from 'lodash';
import $ from 'jquery';

$(() => {
  const $form = $('#signup-modal form');
  modals.init($form, (data) => {
    $form.find(':input[name]').each(function() {
      const $label = $(this).parent('label');
      const fieldName = $(this).attr('name');
      const errorMaybe = _.has(data.errors, fieldName)
        ? {message: _.join(data.errors[fieldName], '<br>')}
        : null;
      modals.mutateField($label, errorMaybe);
    });
  });
});
