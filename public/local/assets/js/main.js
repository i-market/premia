import dom2hscript from 'dom2hscript';

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

// TODO es6 modules
$(() => {
  const formSpecs = window._formSpecs;

  const el = $('form[name=re_call]')[0];

  // virtualize server-rendered dom


  const template = Twig.twig({data: $('#form-template').text()});
  const validator = new FormValidator('re_call', [{
    name: 'name',
    display: 'required',
    rules: 'required'
  }], function(errors, event) {
    const form = _.cloneDeep(formSpecs['re_call']);
    const ctx = _.update(form, 'fields', (fields) => {
      return _.map(fields, (field) => {
        const error = _.find(errors, {name: field.name});
        return error ? _.set(field, 'error', error) : field;
      });
    });
    const html = template.render(ctx);
    // TODO https://github.com/TimBeyer/html-to-vdom
    const tree = eval('var h = virtualDom.h;' + dom2hscript.parseDOM(el));
    const newTree = eval('var h = virtualDom.h;' + dom2hscript.parseHTML(html));
    virtualDom.patch(el, virtualDom.diff(tree, newTree))
  });

  const validate = validator._validateForm.bind(validator);
  $('form[name=re_call]').focusout((event) => {
    _.delay(() => {
      validate(event);
    }, 1000);
  });
  window.validate = validate;
});
