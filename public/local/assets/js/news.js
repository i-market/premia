// TODO require jquery?

function fetchNewsItem(id, cb) {
  return $.get(`/api/news/${id}.html`, cb);
}

// from mockup
function showModal($modal) {
  $modal.fadeIn(100);
  $($modal).find('.block').fadeIn(100);
  $('html, body').css({
    overflow: 'hidden'
  });
}

function initNews($list, $modal) {
  // $list.find('.news_item .text').dotdotdot();
  const contentSel = '.wrap_news_content';
  // loading screen
  const initialContentHtml = $modal.find(contentSel)[0].outerHTML;
  $list.find('.news_item').each(function() {
    $(this).on('click', function() {
      const id = $(this).data('item-id');
      $modal.find(contentSel).replaceWith(initialContentHtml);
      showModal($modal);
      fetchNewsItem(id, (html) => {
        $modal.find(contentSel).replaceWith(html);
        // attach item handlers
      });
    });
  });
}

export default {initNews, fetchNewsItem};