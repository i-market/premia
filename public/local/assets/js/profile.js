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