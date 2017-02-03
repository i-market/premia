import $ from 'jquery';

function scaleScheme($section) {
  const $scheme = $section.find('.block_scheme');
  if (window.matchMedia('(min-width: 1280px) and (min-height: 900px)').matches) {
    const available = $($section).height() - $section.find('h3').outerHeight(true);
    const scaleBy = available / $scheme.height();
    if (scaleBy >= 1) {
      $scheme.css('transform', `scale(${scaleBy})`);
    }
  } else {
    $scheme.css('transform', 'none');
  }
}

function initScheme($section) {
  scaleScheme($section);
  $(window).on('resize', () => {
    scaleScheme($section);
  });
}

export default {initScheme}