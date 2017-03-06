import modals from './modals';

function notifyOnChange($form) {
  $form.on('input change', () => {
    const msg = 'Данные были изменены, для сохранения нажмите кнопку «Сохранить».';
    modals.mutateMessage($form, msg, 'info');
  })
}

function init($component) {
  const successMessage = 'Ваши изменения были сохранены.';
  $component.find('form').each(function() {
    notifyOnChange($(this));
  });
  {
    const $form = $component.find('form.contact_details');
    modals.init($form, (data) => {
      if (data.isSuccess) {
        modals.mutateMessage($form, successMessage, 'success');
      } else {
        modals.mutateMessage($form, data.errorMessageMaybe, 'error');
      }
    });
  }
  {
    const $form = $component.find('form.application_form');
    modals.init($form, (data) => {
      if (data.isSuccess) {
        modals.mutateMessage($form, successMessage, 'success');
      } else {
        // TODO error message
        modals.mutateMessage($form, '', 'error');
      }
    });
  }
}

export default {init};