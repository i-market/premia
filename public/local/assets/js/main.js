import _ from 'lodash';
import twig from 'twig';
import vdom from 'virtual-dom';
import vnode from 'virtual-dom/vnode/vnode';
import vtext from 'virtual-dom/vnode/vtext';
import htmlToVdom from 'html-to-vdom';
import createElement from 'virtual-dom/create-element';

const virtualizeHtml = htmlToVdom({VNode: vnode, VText: vtext});

window.App = {
  googleMapsCallback: () => {
    const markerLatLng = { lat: 55.470993, lng: 37.712681 };
    const map = new google.maps.Map($('#contacts-map')[0], {
      zoom: 13,
      center: markerLatLng,
      scrollwheel: false
    });
    new google.maps.Marker({
      map: map,
      position: markerLatLng
    });
  }
};

$(() => {
  const formSpecs = window._formSpecs;
  const state = {};

  function virtualize(html) {
    return virtualizeHtml({
      getVNodeKey: function (attributes) {
        // now we won't lose focus on form inputs
        return attributes.name;
      }
    }, html);
  }

  function fromSpec(spec) {
    return _.flatMap(spec.validations, (validation) => {
      const cases = {
        // TODO ad-hoc
        required: () => {
          return _.map(validation.fields, (field) => {
            return {
              name: field,
              display: 'required',
              rules: 'required'
            }
          });
        }
      };
      return cases[validation.type]();
    });
  }

  const $form = $('form[name=re_call]');
  const template = twig.twig({data: _.trim($('#form-template').text())});
  state.vtree = virtualize(template.render(formSpecs['re_call']));
  $form.replaceWith(createElement(state.vtree));
  // TODO optimize?
  // get new dom element
  const el = $('form[name=re_call]')[0];
  const spec = formSpecs['re_call'];

  function context(spec, errors) {
    return _.update(_.cloneDeep(spec), 'fields', (fields) => {
      return _.map(fields, (field) => {
        const error = _.find(errors, {name: field.name});
        if (error) {
          const messageTemplate = _.find(spec.validations, (validation) => {
            return validation.type === error.rule && _.includes(validation.fields, field.name);
          })['message'];
          const message = twig.twig({data: messageTemplate}).render(field);
          return _.set(field, 'error', _.set(error, 'message', message));
        } else {
          return field;
        }
      });
    });
  }

  function onValidate(errors, event) {
    const ctx = context(spec, errors);
    const newTree = virtualize(template.render(ctx));
    vdom.patch(el, vdom.diff(state.vtree, newTree));
    state.vtree = newTree;
  }

  const validator = new FormValidator('re_call', fromSpec(spec), onValidate);

  const validate = validator._validateForm.bind(validator);
  $('form[name=re_call]').focusout((event) => {
    validate(event);
  });
});
