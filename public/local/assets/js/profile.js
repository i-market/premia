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

function initForm($form, onSuccess) {
  $form.on('submit', (event) => {
    event.preventDefault();
    $.ajax({
      url: $form.attr('action'),
      type: 'POST',
      data: new FormData($form[0]),
      processData: false,
      contentType: false,
      success: onSuccess,
      // TODO handle errors
      error: _.noop
    });
  })
}

function init($component) {
  const successMessage = 'Ваши изменения были сохранены.';
  $component.find('form').each(function() {
    notifyOnChange($(this));
  });
  function onSuccessFn($form) {
    return (data) => {
      if (_.has(data, 'type')) {
        modals.mutateMessage($form, data.message, data.type);
      } else if (data.isSuccess) {
        modals.mutateMessage($form, successMessage, 'success');
      } else {
        modals.mutateMessage($form, data.errorMessageMaybe, 'error');
      }
    };
  }
  {
    const $form = $component.find('form.contact_details');
    modals.init($form, onSuccessFn($form));
  }
  {
    // application, personal tabs
    $component.find('form.application_form').each(function() {
      const $form = $(this);
      initForm($form, onSuccessFn($form));
    });
  }
  $('.wrap_add_file').each(function() {
    const $root = $(this);
    $root.find('.attach').on('click', () => {
      const key = $root.data('name');
      const $input = $(`<input name="${key}[file][]" class="file" type="file" hidden="hidden">`);
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
  $component.find('[data-tabLinks]').each(function() {
    $(this).attr('data-scroll-to', 'true');
    $(this).on('click', () => {
      $component.find('.form-message.success').hide();
    });
  });
}

export default {init};