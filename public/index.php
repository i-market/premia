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
<section class="nine">
  <div class="wrap">
    <div class="inner">
      <div class="grid">
        <div class="col col_3">
          <span class="number">9</span>
          <h2>номинаций<br>премии</h2>
        </div>
        <div class="col right">
          <div class="item">
            <h4>Гран-при премии:</h4>
            <p>Поставщик-Лидер года</p>
          </div>
          <div class="item">
            <h4>Персональные номинации:</h4>
            <p>Профессионал продаж (Коммерческий директор / Директор по продажам)</p>
            <p>Мастер защиты интересов поставщика (Руководитель юридического департамента)</p>
          </div>
          <div class="item">
            <h4>Для компаний-поставщиков:</h4>
            <p>Лучший поставщик в категории "Малый и средний бизнес"</p>
            <p>Прорыв года</p>
            <p>Профессионал ЭТП</p>
            <p>Лидер импортозамещения</p>
            <p>Инновации в продажах</p>
            <p>Экспортер года</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
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