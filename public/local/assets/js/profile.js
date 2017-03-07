import modals from './modals';
import _ from 'lodash';

// TODO refactor: rename
function notifyOnChange($form) {
  $form.on('input change', (event) => {
    const parts = event.target.name.split('[');
    const isIblockInput = parts.length > 1;
    if (isIblockInput) {
      const key = _.first(parts);
      const name = key + '[is_dirty]';
      if (!$form.find(`input[name="${name}"]`).length) {
        $form.append(`<input name="${name}" type="hidden" value="true">`);
      }
    }
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
    // application, personal tabs
    $component.find('form.application_form').each(function() {
      const $form = $(this);
      modals.init($form, (data) => {
        if (data.isSuccess) {
          modals.mutateMessage($form, successMessage, 'success');
        } else {
          modals.mutateMessage($form, data.errorMessageMaybe, 'error');
        }
      });
    });
  }
  $('.wrap_add_file').each(function() {
    const $root = $(this);
    $root.find('.attach').on('click', () => {
      const $input = $('<input name="file[]" class="file" type="file" hidden="hidden">');
      $root.append($input);
      $input.click();
    });
    $root.on('change', 'input.file', function() {
      const $input = $(this);
      const filename = $input[0].files[0].name;
      $root.find('.add_file').append(
        $(`<p>${filename} <span class="remove_file">(удалить)</span></p>`)
          .on('click', '.remove_file', function() {
            $input.remove();
            $(this).parent('p').remove();
          })
      );
    });
  });
}

export default {init};