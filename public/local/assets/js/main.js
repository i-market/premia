import modals from './modals';
import $ from 'jquery';

$(() => {
  const $form = $('#signup-modal form');
  modals.init($form, (data) => {
    modals.mutateForm($form, data);
  });
});
