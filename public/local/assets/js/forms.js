import modals from './modals';

function notifyOnChange($form, message = null) {
  $form.on('input change', () => {
    const msg = message || 'Данные были изменены, для сохранения нажмите кнопку «Сохранить».';
    modals.mutateMessage($form, msg, 'info');
  })
}

export default {notifyOnChange};
