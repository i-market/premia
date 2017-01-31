import _ from 'lodash';
import twig from 'twig';
import vdom from 'virtual-dom';
import vnode from 'virtual-dom/vnode/vnode';
import vtext from 'virtual-dom/vnode/vtext';
import htmlToVdom from 'html-to-vdom';
import createElement from 'virtual-dom/create-element';

function virtualize(html) {
  const options = {
    getVNodeKey: function (attributes) {
      return _.get(attributes, 'attributes.data-vdom-key');
    }
  };
  return htmlToVdom({VNode: vnode, VText: vtext})(options, html);
}

function context(spec, errors) {
  return _.update(_.cloneDeep(spec), 'fields', (fields) => {
    return _.map(fields, (field) => {
      const error = errors[field.name];
      if (error) {
        return _.set(field, 'error', _.set(error, 'message', error.message));
      } else {
        return field;
      }
    });
  });
}

function formFields($form) {
  return _.reduce($form.serializeArray(), (m, field) => {
    return _.set(m, field.name, field.value);
  }, {});
}

function validate(form) {
  // TODO optimize?
  const $form = $(form.selector).find('form').addBack('form');
  const fields = formFields($form);
  // TODO optimize
  const errors = _.reduce(fields, (acc, value, field) => {
    const requiredValidation = _.find(form.spec.validations, (validation) => {
      return validation.type === 'required' && _.includes(validation.fields, field);
    });
    if (requiredValidation && _.trim(value) === '') {
      const fieldSpec = _.find(form.spec.fields, {name: field});
      const message = twig.twig({data: requiredValidation.message}).render(fieldSpec);
      return _.set(acc, field, {message});
    } else {
      return acc;
    }
  }, {});
  return errors;
}

function update(form, state, context) {
  const newTree = virtualize(form.template.render(context));
  const element = $(form.selector)[0];
  const diff = vdom.diff(state.vtree, newTree);
  vdom.patch(element, diff);
  form.afterUpdate(form);
  state.vtree = newTree;
}

function showErrorsBefore(field, errors, spec) {
  const fields = _.map(spec.fields, 'name');
  const showFields = _.take(fields, _.indexOf(fields, field));
  return _.pick(errors, showFields);
}

function mountForm(selector, spec, afterMounting, afterUpdate) {
  const form = {
    selector,
    spec,
    afterUpdate,
    // TODO decouple template
    template: twig.twig({data: _.trim($('#form-template').text())})
  };
  const state = {};
  state.vtree = virtualize(form.template.render(form.spec));
  $(selector).replaceWith(createElement(state.vtree));
  const $form = $(selector).find('form').addBack('form');
  function onUserInput(event) {
    const errors = validate(form);
    // TODO improve ux
    const ctxErrors = event.type === 'blur' && _.get(event, 'relatedTarget.name')
      ? showErrorsBefore(_.get(event, 'relatedTarget.name'), errors, spec)
      : errors;
    const ctx = _.merge(_.cloneDeep(form.spec), context(form.spec, ctxErrors));
    update(form, state, ctx);
    return errors;
  }
  $form.find(':input').on('blur', (event) => {
    if (_.get(event, 'relatedTarget.type') !== 'submit') {
      onUserInput(event);
    }
  });
  $form.on('submit', (evt) => {
    evt.preventDefault();
    const errors = onUserInput(evt);
    if (_.isEmpty(errors)) {
      $.post(spec.action, formFields($form), (errors) => {
        // TODO check for errors
        const ctx = _.merge(_.cloneDeep(form.spec), {is_success: true});
        update(form, state, ctx);
      });
    }
  });
  afterMounting(form);
}

export default {mountForm};
