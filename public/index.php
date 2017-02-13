<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("1С-Битрикс: Управление сайтом");
use App\Homepage;
use Core\View as v;
?>

<section class="about">
  <div class="wrap">
    <div class="inner editable-area">
      <? $APPLICATION->IncludeComponent(
      	"bitrix:main.include",
      	"",
      	Array(
      		"AREA_FILE_SHOW" => "file",
      		"PATH" => v::includedArea('homepage/about.php')
      	)
      ); ?>
    </div>
  </div>
</section>
<?= Homepage::renderNominations() ?>
<section class="participate_contest">
  <div class="wrap">
    <div class="inner">
      <? $APPLICATION->IncludeComponent(
      	"bitrix:main.include",
      	"",
      	Array(
      		"AREA_FILE_SHOW" => "file",
      		"PATH" => v::includedArea('homepage/how_to_participate.php')
        ),
        null,
        array('HIDE_ICONS' => 'Y')
      ); ?>
      <div class="editable-area">
        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => v::includedArea('homepage/how-to.php')
            )
        ); ?>
      </div>
    </div>
  </div>
</section>
<?= Homepage::renderPartners() ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>