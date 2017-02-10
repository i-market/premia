<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("1С-Битрикс: Управление сайтом");
use Core\View as v;
?>

<section class="about">
  <div class="wrap">
    <div class="inner">
      <h2>О премии</h2>
      <p><b>Премия «Лидер конкурентных продаж»</b> – общественный знак отличия для успешных поставщиков в различных отраслях российской экономики. Конкурс определяет лучшие практики рынка, демонстрирует позитивные примеры организации поставок любого масштаба.</p>
      <p><b>Ключевая цель премии</b> – привлечение внимания к наиболее значимым проектам в области поставок, которые повышают эффективность работы компаний и вносят существенный вклад в развитие всего рынка и экономики страны в целом.</p>
      <br>
      <br>
      <h3>Кто может принять участие?</h3>
      <ul class="list">
        <li>Компании-поставщики товаров, работ и услуг для корпоративного и государственного сектора</li>
        <li>Коммерческие директора/директора по продажам компаний-поставщиков</li>
        <li>Руководители юридических департаментов компаний-поставщиков</li>
      </ul>
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
            <a class="btn--blue" href="#">Зарегистрироваться</a>
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
      <h3>Как определяются победители</h3>
      <ul class="list">
        <li>Все конкурсные материалы получают оценку независимого экспертного сообщества, авторы лучших работ становятся номинантами Премии. Из их числа выбираются победители в соответствии с положением о премии.</li>
      </ul>
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