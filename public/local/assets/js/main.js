import modals from './modals';
import profile from './profile';
import $ from 'jquery';
import _ from 'lodash';

$(() => {
  // TODO get it from the server
  const profilePath = '/auth/profile/';

  {
    // TODO disable button on `submit`?
    const $form = $('#signup-modal form');
    modals.init($form, (data) => {
      modals.mutateForm($form, data);
      const isSuccess = _.get(data, 'bxMessage.type') === 'OK';
      if (isSuccess) {
        modals.toggleSubmitButton($form, false);
        setTimeout(() => {
          window.location.href = window.location.origin + profilePath;
        }, 2000);
      }
    });
  }

  {
    const $form = $('#login-modal form');
    modals.init($form, (data) => {
      modals.mutateForm($form, data);
      if (data.isLoggedIn) {
        window.location.href = window.location.origin + profilePath;
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

  {
    const $form = $('#contact-modal form');
    modals.init($form, (data) => {
      modals.mutateForm($form, data);
      const isSuccess = _.isEmpty(data.errors);
      if (isSuccess) {
        modals.mutateMessage($form, 'Ваше сообщение было успешно отправлено. Спасибо.', 'success');
        modals.toggleSubmitButton($form, false);
      }
    });
  }

  // TODO refactor: optimize
  profile.init($('.personal_area'));
});
