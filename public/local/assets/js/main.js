import modals from './modals';
import $ from 'jquery';
import _ from 'lodash';

$(() => {
  const signupRedirectPath = '/';

  const $form = $('#signup-modal form');
  modals.init($form, (data) => {
    modals.mutateForm($form, data);
    const isSuccess = _.get(data, 'bxMessage.type') === 'OK';
    if (isSuccess) {
      window.location.href = window.location.origin + signupRedirectPath;
    }
  });
});
