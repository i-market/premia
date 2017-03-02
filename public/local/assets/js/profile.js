import modals from './modals';

function init($component) {
  const $form = $component.find('form');
  $form.on('input change', () => {
    const msg = 'Данные были изменены, для сохранения нажмите кнопку «Сохранить».';
    modals.mutateMessage($form, msg, 'info');
  });
  modals.init($form, (data) => {
    if (data.isSuccess) {
      modals.mutateMessage($form, 'Ваши изменения были сохранены.', 'success');
    } else {
      modals.mutateMessage($form, data.errorMessageMaybe, 'error');
    }
  });
}

export default {init};