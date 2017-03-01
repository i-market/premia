import modals from './modals';
import $ from 'jquery';
import _ from 'lodash';

$(() => {
  // TODO user profile path, get it from the server
  const signupRedirectPath = '/';

  {
    // TODO disable button on `submit`?
    const $form = $('#signup-modal form');
    modals.init($form, (data) => {
      modals.mutateForm($form, data);
      const isSuccess = _.get(data, 'bxMessage.type') === 'OK';
      if (isSuccess) {
        modals.toggleSubmitButton($form, false);
        setTimeout(() => {
          window.location.href = window.location.origin + signupRedirectPath;
        }, 2000);
      }
    });
  }

  {
    const $form = $('#login-modal form');
    modals.init($form, (data) => {
      modals.mutateForm($form, data);
      if (data.isLoggedIn) {
        window.location.reload();
      }
    });
  }

  {
    const $form = $('#password-reset-modal form');
    modals.init($form, (data) => {
      modals.mutateForm($form, data);
      const isSuccess = _.get(data, 'bxMessage.type') === 'OK';
      if (isSuccess) {
        modals.toggleSubmitButton($form, false);
      }
    });
  }
});
