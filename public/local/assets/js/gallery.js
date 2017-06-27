import $ from 'jquery';
import _ from 'lodash';
import objectFitImages from 'object-fit-images';

function activate($component, sectionId) {
  $component.find('.gallery-slider').each(function() {
    $(this).toggleClass('active', $(this).attr('data-section-id') === sectionId);
  });
  const $prev = $component.find('.album.active');
  const $next = $component.find(`.album[data-section-id="${sectionId}"]`);
  $next.after($prev);
  _.forEach([$prev, $next], function($el) {
    $el.toggleClass('active', $el.attr('data-section-id') === sectionId);
  });
  objectFitImages();
}

function initSlider($slider) {
  objectFitImages();
  $slider.find('.slick').slick();
  $slider.find('[data-fancybox]:not(.slick-cloned)').fancybox();
}

function init($component) {
  initSlider($component.find('.gallery-slider'));
  $component.find('.album').on('click', function() {
    const sectionId = $(this).attr('data-section-id');
    const isSectionLoaded = $component.find(`.gallery-slider[data-section-id="${sectionId}"]`).length;
    if (!isSectionLoaded) {
      $.get(`/api/gallery/${sectionId}.html`, (html) => {
        const $slider = $(html);
        $component.prepend($slider);
        activate($component, sectionId);
        initSlider($slider);
      });
    } else {
      activate($component, sectionId);
      const $slider = $component.find('.gallery-slider.active');
      $slider.find('.slick').slick('reinit');
    }
  });
}

export default {init};
