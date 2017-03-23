import modals from './modals';
import profile from './profile';
import vote from './vote';
import gallery from './gallery';
import $ from 'jquery';
import _ from 'lodash';

$(() => {
  // TODO get it from the server
  const profilePath = '/auth/profile/';

  {
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

  // TODO refactor
  profile.init($('.personal_area'));
  vote.init($('.application-vote'));
  gallery.init($('.gallery'));

  $('.personal_area, .expert-profile').each(function() {
    const $component = $(this);
    $component.find('[data-tabLinks]').each(function() {
      $(this).attr('data-scroll-to', 'true');
      $(this).on('click', () => {
        $component.find('.form-message.success').hide();
      });
    });
  });

  const $globalLoader = $('#global-loader');
  var timer = null;
  $(document)
    .ajaxStart(() => {
      timer = setTimeout(() => {
        $globalLoader.show();
      }, 500);
    })
    .ajaxStop(() => {
      if (timer !== null) {
        clearTimeout(timer);
      }
      $globalLoader.hide();
    })
});
