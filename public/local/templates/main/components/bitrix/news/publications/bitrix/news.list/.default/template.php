<? // TODO tmp whitespace fix, remove when breadcrumbs are done ?>
<section class="bread_crumbs">
  <div class="wrap">
    <div class="inner">
      <ul>
      </ul>
    </div>
  </div>
</section>
<section class="publications">
  <div class="wrap">
    <div class="inner">
      <h2>Публикации</h2>
      <? foreach ($arResult['ITEMS'] as $item): ?>
        <div class="item">
          <ul class="list">
            <li><a href="<?= $item['DETAIL_PAGE_URL'] ?>"><?= $item['NAME'] ?></a></li>
            <div class="text editable-area"><?= $item['PREVIEW_TEXT'] ?></div>
            <? // TODO name ?>
            <? // <p class="name">(Никифоров Евгений)</p> ?>
          </ul>
        </div>
      <? endforeach ?>
    </div>
  </div>
</section>