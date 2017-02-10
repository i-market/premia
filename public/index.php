<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("1С-Битрикс: Управление сайтом");
use App\Auth;
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
      <h2>Для участия в конкурсе:</h2>
      <div class="grid">
        <div class="col col_3">
          <p class="title">
            <span>1</span>Зарегистрируйтесь на нашем сайте
          </p>
          <div class="block">
            <a class="btn--blue" href="<?= Auth::signupPath() ?>">Зарегистрироваться</a>
          </div>
        </div>
        <div class="col col_3">
          <p class="title">
            <span>2</span>Заполните заявку в личном кабинете
          </p>
          <div class="block">
            <img src=<?= v::asset('images/pic_2.png') ?> alt="">
          </div>
        </div>
        <div class="col col_3">
          <p class="title">
            <span>3</span>Ждите письма с итогами конкурса
          </p>
          <div class="block">
            <img src=<?= v::asset('images/pic_3.png') ?> alt="">
          </div>
        </div>
      </div>
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
<section class="partners">
  <div class="wrap">
    <div class="carusel">
      <div>
        <img src=<?= v::asset('images/part_1.png') ?> alt="">
      </div>
      <div>
        <img src=<?= v::asset('images/part_2.png') ?> alt="">
      </div>
      <div>
        <img src=<?= v::asset('images/part_3.png') ?> alt="">
      </div>
      <div>
        <img src=<?= v::asset('images/part_4.png') ?> alt="">
      </div>
      <div>
        <img src=<?= v::asset('images/part_5.png') ?> alt="">
      </div>
      <div>
        <img src=<?= v::asset('images/part_1.png') ?> alt="">
      </div>
      <div>
        <img src=<?= v::asset('images/part_2.png') ?> alt="">
      </div>
      <div>
        <img src=<?= v::asset('images/part_3.png') ?> alt="">
      </div>
      <div>
        <img src=<?= v::asset('images/part_4.png') ?> alt="">
      </div>
      <div>
        <img src=<?= v::asset('images/part_5.png') ?> alt="">
      </div>
    </div>
  </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>