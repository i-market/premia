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
                <h1>Четыре сезона</h1>
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
                <div class="item col_5">
                    <img src="<?= v::asset('images/Group-11.png') ?>" alt="">
                    <p>Бакалея</p>
                </div>
                <? // third row ?>
                <div class="item col_5">
                    <img src="<?= v::asset('images/Group-03.png') ?>" alt="">
                    <p>Мясо</p>
                </div>
                <div class="item col_5">
                    <img src="<?= v::asset('images/Group-05.png') ?>" alt="">
                    <p>Птица</p>
                </div>
                <div class="item col_5">
                    <img src="<?= v::asset('images/Group-07.png') ?>" alt="">
                    <p class="multiline">Рыба</p>
                </div>
                <div class="item col_5">
                    <img src="<?= v::asset('images/Group-09.png') ?>" alt="">
                    <p class="multiline">Молочная<br> продукция</p>
                </div>
                <a href="javascript:void(0)" data-modal="price_request" class="item item--download col_5">
                    <img src="<?= v::asset('images/tmp/price-black.png') ?>" alt="">
                    <p>Запросить<br> цены</p>
                </a>
            </div>
        </div>
    </div>
</section>
<section class="brands">
    <span class="anchor" id="three"></span>
    <div class="wrap">
        <h3>Торговый дом</h3>
        <p class="text">На территории оптово-распределительного центра
            <span>с  2014 года функционирует Торговый дом ОРЦ "Четыре сезона".</span> Удобное месторасположение и современный подход к выполнению поставленных задач зарекомендовал Торговый Дом ОРЦ “Четыре сезона”, как надежного и оперативного партнера.</p>
        <p class="partners_text">Наши торговые партнёры</p>
        <div class="partners_block">
            <img src="<?= v::asset('images/brend_1.png') ?>" alt="">
            <img src="<?= v::asset('images/brend_2.png') ?>" alt="">
            <img src="<?= v::asset('images/brend_3.png') ?>" alt="">
            <img src="<?= v::asset('images/brend_4.png') ?>" alt="">
            <img src="<?= v::asset('images/brend_5.png') ?>" alt="">
            <img src="<?= v::asset('images/brend_6.png') ?>" alt="">
            <img src="<?= v::asset('images/brend_7.png') ?>" alt="">
            <img src="<?= v::asset('images/brend_8.png') ?>" alt="">
            <img src="<?= v::asset('images/brend_9.png') ?>" alt="">
        </div>
    </div>
</section>
<section class="import_manufacturers">
    <span class="anchor" id="four"></span>
    <div class="wrap">
        <div class="top">
            <strong>Более 30 контрактов</strong>
            <p>с импортными производителями</p>
            <span>говорят о том, что мы являемся успешными импортерами
с/х продукции на российский рынок.</span>
        </div>
        <div class="grid">
            <div class="col_4 item">
                <img src="<?= v::asset('images/vegetables_1.png') ?>" alt="">
                <p>Овощи</p>
            </div>
            <div class="col_4 item">
                <img src="<?= v::asset('images/vegetables_2.png') ?>" alt="">
                <p>Фрукты</p>
            </div>
            <div class="col_4 item">
                <img src="<?= v::asset('images/vegetables_3.png') ?>" alt="">
                <p>Ягоды</p>
            </div>
            <div class="col_4 item">
                <img src="<?= v::asset('images/vegetables_4.png') ?>" alt="">
                <p>Экзотика</p>
            </div>
        </div>
        <div class="wrap_flags">
            <div class="flags">
                <div class="flag"><img src="<?= v::asset('images/flag_1.png') ?>" alt=""></div>
                <div class="flag"><img src="<?= v::asset('images/flag_2.png') ?>" alt=""></div>
                <div class="flag"><img src="<?= v::asset('images/flag_3.png') ?>" alt=""></div>
                <div class="flag"><img src="<?= v::asset('images/flag_4.png') ?>" alt=""></div>
                <div class="flag"><img src="<?= v::asset('images/flag_5.png') ?>" alt=""></div>
                <div class="flag"><img src="<?= v::asset('images/flag_6.png') ?>" alt=""></div>
                <div class="flag"><img src="<?= v::asset('images/flag_7.png') ?>" alt=""></div>
                <div class="flag"><img src="<?= v::asset('images/flag_8.png') ?>" alt=""></div>
                <div class="flag"><img src="<?= v::asset('images/flag_9.png') ?>" alt=""></div>
                <div class="flag"><img src="<?= v::asset('images/flag_10.png') ?>" alt=""></div>
                <div class="flag"><img src="<?= v::asset('images/flag_11.png') ?>" alt=""></div>
                <div class="flag"><img src="<?= v::asset('images/flag_12.png') ?>" alt=""></div>
                <div class="flag"><img src="<?= v::asset('images/flag_13.png') ?>" alt=""></div>
                <div class="flag"><img src="<?= v::asset('images/flag_14.png') ?>" alt=""></div>
                <div class="flag"><img src="<?= v::asset('images/flag_15.png') ?>" alt=""></div>
                <div class="flag"><img src="<?= v::asset('images/flag_16.png') ?>" alt=""></div>
            </div>
            <div class="flag_text">
                <span>Азербайджан</span>
                <span>Аргентина</span>
                <span>Армения</span>
                <span>Грузия</span>
                <span>Египет</span>
                <span>Израиль</span>
                <span>Индия</span>
                <span>Иран</span>
                <span>Казахстан</span>
                <span>Марокко</span>
                <span>Пакистан</span>
                <span>Сербия</span>
                <span>Такжикистан</span>
                <span>Узбекистан</span>
                <span>Чили</span>
                <span>ЮАР</span>
            </div>
        </div>
    </div>
</section>
<section class="why_buy_from_us">
    <span class="anchor" id="five"></span>
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
                        <p>Прямые поставки от отечественных и зарубежных поставщиков обеспечивают самые выгодные цены на продукцию</p>
                        <span class="metro_btn">Подробнее</span>
                    </div>
                </div>
                <div class="back">
                    <div class="box2">
                        <strong>Цены и ассортимент</strong>
                        <p>У нас представлена продукция 2500 отечественных и иностранных производителей</p>
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
                        <p>Передовые технологии хранения обеспечивают сохранность лучших качеств и свежести продуктов</p>
                        <span class="metro_btn">Подробнее</span>
                    </div>
                </div>
                <div class="back">
                    <div class="box2">
                        <strong>Качество<br>продукции</strong>
                        <p>Мы адаптировали опыт мировых лидеров в сфере товарного распределения под российские стандарты</p>
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
                        <p>Мы делаем все возможное, чтобы вы остались довольны сотрудничеством с нами</p>
                        <span class="metro_btn">Подробнее</span>
                    </div>
                </div>
                <div class="back">
                    <div class="box2">
                        <strong>Удобный<br>сервис</strong>
                        <p>Отлаженные процессы на всех этапах делают сотрудничество с нами максимально удобным</p>
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
                        <strong>Любые<br> объемы</strong>
                        <span class="line"></span>
                        <p>Весь ассортимент возможно приобрести мелкооптовыми и крупнооптовыми партиями</p>
                        <span class="metro_btn">Подробнее</span>
                    </div>
                </div>
                <div class="back">
                    <div class="box2">
                        <strong>Любые<br> объемы</strong>
                        <p>Гибкость в продажах позволяет нам удовлетворять интересы как крупных сетевых ритейлеров, так и розничных покупателей</p>
                        <span class="metro_btn">Назад</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="who_buy_from_us">
    <span class="anchor" id="six"></span>
    <div class="wrap">
        <h3>кто покупает у нас</h3>
        <div class="grid">
            <div class="item col_5">
                <div class="img">
                    <img src="<?= v::asset('images/pic_5.jpg') ?>" alt="">
                </div>
                <div class="info">
                    <div class="ico">
                        <img src="<?= v::asset('images/ico_101.png') ?>" alt="">
                    </div>
                    <span class="line"></span>
                    <strong>HoReCa</strong>
                </div>
            </div>
            <div class="item col_5">
                <div class="img">
                    <img src="<?= v::asset('images/pic_6.jpg') ?>" alt="">
                </div>
                <div class="info">
                    <div class="ico">
                        <img src="<?= v::asset('images/ico_102.png') ?>" alt="">
                    </div>
                    <span class="line"></span>
                    <strong>Федеральные торговые сети</strong>
                </div>
            </div>
            <div class="item col_5">
                <div class="img">
                    <img src="<?= v::asset('images/pic_7.jpg') ?>" alt="">
                </div>
                <div class="info">
                    <div class="ico">
                        <img src="<?= v::asset('images/ico_102.png') ?>" alt="">
                    </div>
                    <span class="line"></span>
                    <strong>Региональные оптовые покупатели</strong>
                </div>
            </div>
            <div class="item col_5">
                <div class="img">
                    <img src="<?= v::asset('images/pic_7.jpg') ?>" alt="">
                </div>
                <div class="info">
                    <div class="ico">
                        <img src="<?= v::asset('images/ico_102.png') ?>" alt="">
                    </div>
                    <span class="line"></span>
                    <strong>Мелкооптовые и крупнооптовые покупатели</strong>
                </div>
            </div>
            <div class="item col_5">
                <div class="img">
                    <img src="<?= v::asset('images/pic_7.jpg') ?>" alt="">
                </div>
                <div class="info">
                    <div class="ico">
                        <img src="<?= v::asset('images/ico_103.png') ?>" alt="">
                    </div>
                    <span class="line"></span>
                    <strong>Розничные покупатели</strong>
                </div>
            </div>
        </div>
    </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>