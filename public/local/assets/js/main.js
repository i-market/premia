import _ from 'lodash';
import twig from 'twig';
import vdom from 'virtual-dom';
import vnode from 'virtual-dom/vnode/vnode';
import vtext from 'virtual-dom/vnode/vtext';
import htmlToVdom from 'html-to-vdom';
import createElement from 'virtual-dom/create-element';
import FormValidator from 'validate-js';
import enquire from 'enquire.js';

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
  function initRentalOffers() {
    const $rentalItems = $('.rental_offers .item');
    const $buttons = $rentalItems.find('.bottom');
    const matchHeightQuery = 'only screen and (min-width: 767px)';
    const state = {
      afterUpdate: _.noop
    };

    function matchButtons() {
      const maxTop = _.max($buttons.map(function () {
        return $(this).position().top;
      }));
      $buttons.each(function () {
        // align all buttons horizontally
        $(this).css('padding-top', maxTop - $(this).position().top);
      });
    }

    function unmatchButtons() {
      $buttons.each(function () {
        $(this).css('padding-top', 0);
      });
    }

    enquire.register(matchHeightQuery, {
      match: () => {
        $rentalItems.matchHeight();
        state.afterUpdate = matchButtons;
      },
      unmatch: () => {
        $rentalItems.matchHeight('remove');
        state.afterUpdate = unmatchButtons;
      }
    });
    // TODO replace with css
    $.fn.matchHeight._afterUpdate = _.debounce((event, groups) => {
      state.afterUpdate();
    }, 100);
  }

  initRentalOffers();

  // forms

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

  function onValidate(spec, element, errors, event) {
    const ctx = context(spec, errors);
    const newTree = virtualize(template.render(ctx));
    vdom.patch(element, vdom.diff(state.vtree, newTree));
    onUpdate(spec, element);
    state.vtree = newTree;
  }

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

  const onUpdate = _.debounce((spec, element) => {
    // restore mockup jquery stuff
    window._modals();
    // TODO calling this a lot kills performance, added `debounce` for now
    const validator = new FormValidator('re_call', fromSpec(spec), _.partial(onValidate, spec, element));
    const validate = validator._validateForm.bind(validator);
    $('form[name=re_call]').focusout((event) => {
      // validate.js will validate on submit, don't trigger it twice
      if (_.get(event, 'relatedTarget.type') !== 'submit') {
        validate(event);
      }
    });
  }, 100);

  const $form = $('form[name=re_call]');
  const template = twig.twig({data: _.trim($('#form-template').text())});
  state.vtree = virtualize(template.render(formSpecs['re_call']));
  $form.replaceWith(createElement(state.vtree));
  // TODO optimize?
  // get new dom element
  const spec = formSpecs['re_call'];
  const element = $('form[name=re_call]')[0];
  onUpdate(spec, element);
});
