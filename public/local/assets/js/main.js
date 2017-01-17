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
  const template = Twig.twig({data: $('#form-template').text()});
  const validator = new FormValidator('re_call', [{
    name: 'name',
    display: 'required',
    rules: 'required'
  }], function(errors, event) {
    // TODO hi
    const html = template.render(Object.assign(window._forms.re_call, {title: 'hi'}));
    const el = $('form[name=re_call]')[0];
    const tree = eval('var h = virtualDom.h;' + dom2hscript.parseDOM(el));
    const newTree = eval('var h = virtualDom.h;' + dom2hscript.parseHTML(html));
    virtualDom.patch(el, virtualDom.diff(tree, newTree))
  });

  $('form[name=re_call]').focusout(validator.form.onsubmit);
});
