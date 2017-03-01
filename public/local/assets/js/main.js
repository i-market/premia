import modals from './modals';
import $ from 'jquery';
import _ from 'lodash';

$(() => {
  // TODO user profile path, get it from the server
  const signupRedirectPath = '/';

  {
    const $form = $('#signup-modal form');
    modals.init($form, (data) => {
      modals.mutateForm($form, data);
      const isSuccess = _.get(data, 'bxMessage.type') === 'OK';
      if (isSuccess) {
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
      const isSuccess = _.isEmpty(data.bxMessage);
      if (isSuccess) {
        window.location.reload();
      }
    });
  }
});
