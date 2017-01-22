import _ from 'lodash';
import enquire from 'enquire.js';
import forms from './forms';

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

  _.forEach(['re_call', 'write_letter'], (formName) => {
    const spec = window._formSpecs[formName];
    function afterMounting() {
      // restore mockup jquery stuff
      window._modals();
    }
    forms.mountForm(`form[name="${formName}"]`, spec, afterMounting, _.noop);
  });
});
