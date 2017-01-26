import _ from 'lodash';
import enquire from 'enquire.js';
import forms from './forms';
import news from './news';

window.App = {
  ymapsCallback: () => {
    const markerLatLng = [55.470993, 37.712681];
    const zoom = 12;
    const map = new ymaps.Map($('#contacts-map')[0], {
      // TODO ymaps controls
      controls: [],
      zoom,
      center: markerLatLng
    });
    function setCenter() {
      map.setCenter(markerLatLng, zoom);
      if (window.matchMedia('(min-width: 768px)').matches) {
        const pos = map.getGlobalPixelCenter();
        const width = $('#contacts-map').width();
        const offsetX = (width / 3 * 2) - (width / 2);
        // TODO subtract offset to move right? wat?
        map.setGlobalPixelCenter([pos[0] - offsetX, pos[1]], zoom);
      }
    }
    setCenter();
    $(window).resize(setCenter);
    const marker = new ymaps.Placemark(markerLatLng);
    map.geoObjects.add(marker);
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
  _.forEach(window._formSpecs, (spec) => {
    function afterMounting() {
      // restore mockup jquery stuff
      window._modals();
    }
    forms.mountForm(`form[name="${spec.name}"]`, spec, afterMounting, _.noop);
  });

  $('#news').each(function() {
    news.initNews($(this), $('#news_modal'));
  });
});
