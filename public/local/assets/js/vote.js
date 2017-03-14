import forms from './forms';
import modals from './modals';

function init($component) {
  const $form = $component.find('form');
  forms.notifyOnChange($form, 'Оценки были изменены, для сохранения изменений нажмите на кнопку «Сохранить».');
  modals.init($form, (data) => {
    // TODO don't log
    console.log(data);
    if (data.type === 'success') {

    }
  });
}

export default {init};