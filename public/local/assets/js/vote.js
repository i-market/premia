import forms from './forms';
import modals from './modals';

function init($component) {
  const $form = $component.find('form');
  forms.notifyOnChange($form, 'Оценки были изменены, для сохранения изменений нажмите на кнопку «Сохранить».');
  modals.init($form, (data) => {
    modals.mutateMessage($form, data.message, data.type);
  });
}

export default {init};