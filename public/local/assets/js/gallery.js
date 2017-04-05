import $ from 'jquery';
import objectFitImages from 'object-fit-images';

function activate($component, sectionId) {
  $component.find('.gallery-slider').each(function() {
    $(this).toggleClass('active', $(this).attr('data-section-id') === sectionId);
  });
  objectFitImages();
}

function initSlider($slider) {
  $slider.find('.slick').slick();
  $slider.find('[data-fancybox]:not(.slick-cloned)').fancybox();
  objectFitImages();
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
    }
  });
}

export default {init};
