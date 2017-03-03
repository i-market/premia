<?
use Core\View as v;
?>
<? $APPLICATION->SetPageProperty(\App\PageProperty::LAYOUT, 'base.twig') ?>
<h2><?= $arResult['NAME'] ?></h2>
<?= $arResult['DETAIL_TEXT'] ?>
<? // TODO refactor link ?>
<p><a href="<?= v::path('press-center/publications') ?>">К списку публикаций</a></p>
