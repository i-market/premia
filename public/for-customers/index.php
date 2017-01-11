<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Покупателям");

use Hendrix\View as v;
// TODO prevent editing this
?>

<section class="wrap_slider">
    <div class="wrap_arrows">
        <span class="arrows prev"></span>
        <span class="arrows next"></span>
    </div>
    <div class="slider">
        <div class="slide slide--inner" style="background: url('<?= v::asset('images/slider2.png') ?>')no-repeat center center">
            <div class="block">
                <h1>Четыре сезона</h1>
                <span class="line"></span>
                <h2>Фрукты и овощи от производителя<br>по оптовым ценам</h2>
            </div>
        </div>
        <div class="slide slide--inner" style="background: url('<?= v::asset('images/slider2.png') ?>')no-repeat center center">
            <div class="block">
                <h1>Три сезона</h1>
                <span class="line"></span>
                <h2>Фрукты и овощи от производителя<br>по оптовым ценам</h2>
            </div>
        </div>
    </div>
    <div class="dots"></div>
</section>
<section class="price_list">
    <span class="anchor" id="two"></span>
    <div class="wrap">
        <div class="block">
            <div class="included_area">
                <? $APPLICATION->IncludeComponent(
                	"bitrix:main.include",
                	"",
                	Array(
                		"AREA_FILE_SHOW" => "file",
                		"PATH" => v::includedArea('for-customers/price_list.php')
                	)
                ); ?>
            </div>
            <div class="grid">
                <div class="item col_5">
                    <img src="<?= v::asset('images/Group%2016.png') ?>" alt="">
                    <p>Экзотика</p>
                </div>
                <div class="item col_5">
                    <img src="<?= v::asset('images/Group%2017.png') ?>" alt="">
                    <p>Фрукты</p>
                </div>
                <div class="item col_5">
                    <img src="<?= v::asset('images/Group%2018.png') ?>" alt="">
                    <p>Овощи</p>
                </div>
                <div class="item col_5">
                    <img src="<?= v::asset('images/Group%2019.png') ?>" alt="">
                    <p>Зелень</p>
                </div>
                <div class="item col_5">
                    <img src="<?= v::asset('images/Group%2020.png') ?>" alt="">
                    <p>Ягоды</p>
                </div>
                <div class="item col_5">
                    <img src="<?= v::asset('images/Group%2021.png') ?>" alt="">
                    <p>Грибы</p>
                </div>
                <div class="item col_5">
                    <img src="<?= v::asset('images/Group%2022.png') ?>" alt="">
                    <p>Бахчевые</p>
                </div>
                <div class="item col_5">
                    <img src="<?= v::asset('images/Group%2023.png') ?>" alt="">
                    <p>Орехи</p>
                </div>
                <div class="item col_5">
                    <img src="<?= v::asset('images/Group%2024.png') ?>" alt="">
                    <p>Сухофрукты</p>
                </div>
                <!-- TODO tmp catalog dl link -->
                <!--<a href="#" class="item item--download col_5">-->
<!--                    <img src="--><?//= v::asset('images/download.jpg') ?><!--" alt="">-->
                    <!--<p>Скачать-->
                        <!--<br>прайс лист</p>-->
                    <!--</a>-->
                <div class="item col_5">
                    <img src="<?= v::asset('images/Group%2021.png') ?>" alt="">
                    <p>Грибы</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="why_buy_from_us">
    <span class="anchor" id="three"></span>
    <div class="wrap">
        <h3>Почему покупают у нас</h3>
        <div class="grid">
            <div class="metro col_4">
                <div class="front">
                    <div class="box1">
                        <div class="ico">
                            <img src="<?= v::asset('images/price.png') ?>" alt="">
                        </div>
                        <strong>Цены<br>и ассортимент</strong>
                        <span class="line"></span>
                        <p>Прямые поставки от отечественных и зарубежных поставщиков обеспечивают самые выгодные цены на продукцию.</p>
                        <span class="metro_btn">Подробнее</span>
                    </div>
                </div>
                <div class="back">
                    <div class="box2">
                        <strong>Цены и ассортимент</strong>
                        <p>Российская специфика, как следует из вышесказанного, все еще интересна для многих.
                            <br>Баинг и селлинг концентрирует потребительский формирование имиджа.</p>
                        <span class="metro_btn">Назад</span>
                    </div>
                </div>
            </div>
            <div class="metro col_4">
                <div class="front">
                    <div class="box1">
                        <div class="ico">
                            <img src="<?= v::asset('images/quality.png') ?>" alt="">
                        </div>
                        <strong>Качество<br>продукции</strong>
                        <span class="line"></span>
                        <p>Благодаря транспортной доступности и высокотехнологичным складам вся продукция сохраняет высокое качество, свежесть, зрелость.</p>
                        <span class="metro_btn">Подробнее</span>
                    </div>
                </div>
                <div class="back">
                    <div class="box2">
                        <strong>Цены и ассортимент</strong>
                        <p>Российская специфика, как следует из вышесказанного, все еще интересна для многих.
                            <br>Баинг и селлинг концентрирует потребительский формирование имиджа.</p>
                        <span class="metro_btn">Назад</span>
                    </div>
                </div>
            </div>
            <div class="metro col_4">
                <div class="front">
                    <div class="box1">
                        <div class="ico">
                            <img src="<?= v::asset('images/service.png') ?>" alt="">
                        </div>
                        <strong>Удобный<br>сервис</strong>
                        <span class="line"></span>
                        <p>Находимся в непосредственной близости к трассам федерального значения и маршрутам поставки грузов</p>
                        <span class="metro_btn">Подробнее</span>
                    </div>
                </div>
                <div class="back">
                    <div class="box2">
                        <strong>Цены и ассортимент</strong>
                        <p>Российская специфика, как следует из вышесказанного, все еще интересна для многих.
                            <br>Баинг и селлинг концентрирует потребительский формирование имиджа.</p>
                        <span class="metro_btn">Назад</span>
                    </div>
                </div>
            </div>
            <div class="metro col_4">
                <div class="front">
                    <div class="box1">
                        <div class="ico">
                            <img src="<?= v::asset('images/volume.png') ?>" alt="">
                        </div>
                        <strong>Любой<br>объем</strong>
                        <span class="line"></span>
                        <p>Весь ассортимент продуктов можно приобрести возможно приобрести крупнооптовой и мелкооптовой партиями.</p>
                        <span class="metro_btn">Подробнее</span>
                    </div>
                </div>
                <div class="back">
                    <div class="box2">
                        <strong>Цены и ассортимент</strong>
                        <p>Российская специфика, как следует из вышесказанного, все еще интересна для многих.
                            <br>Баинг и селлинг концентрирует потребительский формирование имиджа.</p>
                        <span class="metro_btn">Назад</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="who_buy_from_us">
    <span class="anchor" id="four"></span>
    <div class="wrap">
        <h3>кто покупает у нас</h3>
        <div class="grid">
            <div class="item col_3">
                <div class="img">
                    <img src="<?= v::asset('images/pic_5.jpg') ?>" alt="">
                </div>
                <div class="info">
                    <div class="ico">
                        <img src="<?= v::asset('images/ico_101.png') ?>" alt="">
                    </div>
                    <strong>HoReCa</strong>
                    <span class="line"></span>
                    <p>Продукты для столовых, кафе, баров, ресторанов по выгодным условиям и гибким ценам.</p>
                </div>
            </div>
            <div class="item col_3">
                <div class="img">
                    <img src="<?= v::asset('images/pic_6.jpg') ?>" alt="">
                </div>
                <div class="info">
                    <div class="ico">
                        <img src="<?= v::asset('images/ico_102.png') ?>" alt="">
                    </div>
                    <strong>Предприниматели</strong>
                    <span class="line"></span>
                    <p>Более 3 500 отечественных и зарубежный поставщиков на одной площадке.</p>
                </div>
            </div>
            <div class="item col_3">
                <div class="img">
                    <img src="<?= v::asset('images/pic_7.jpg') ?>" alt="">
                </div>
                <div class="info">
                    <div class="ico">
                        <img src="<?= v::asset('images/ico_103.png') ?>" alt="">
                    </div>
                    <strong>Покупатели</strong>
                    <span class="line"></span>
                    <p>Сотни наименований овощей и фруктов для мелкооптовой и крупнооптовой закупок 7 дней в неделю.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>