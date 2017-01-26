<?
use Hendrix\View as v;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("О комплексе");
$APPLICATION->SetPageProperty(\App\PageProperty::LAYOUT, 'homepage.twig');
?>

<section class="wrap_slider">
    <div class="wrap_arrows">
        <span class="arrows prev"></span>
        <span class="arrows next"></span>
    </div>
    <div class="slider">
        <div class="slide slide--inner slide--opacity" style="background: url('<?= v::asset('images/slider3.jpg') ?>')no-repeat center center">
            <div class="block">
                <h1>Четыре сезона</h1>
                <span class="line"></span>
                <h2>оптово-распределительный центр</h2>
            </div>
        </div>
        <div class="slide slide--inner slide--opacity" style="background: url('<?= v::asset('images/homepage-slide-1.jpg') ?>')no-repeat center center">
            <div class="block">
                <h1>Четыре сезона</h1>
                <span class="line"></span>
                <h2>оптово-распределительный центр</h2>
            </div>
        </div>
    </div>
    <div class="dots"></div>
</section>
<section class="four_seasons">
    <span class="anchor" id="two"></span>
    <div class="wrap">
        <div class="edited_block">
            <h3>«Четыре сезона»</h3>
            <p>Современный многофункциональный оптово-распределительный центр
                <span>в г. Домодедово  на юге Подмосковья, созданный в 2013 году.</span>
            </p>
            <p>Центр решает задачи централизации услуг по хранению, предпродажной подготовке, распределению продуктов питания во все регионы России. В его основе заложены технологии мировых лидеров в сфере товарного распределения, адаптированные под российские стандарты.</p>
        </div>
        <div class="grid">
            <div class="col_3 item">
                <div class="number">1500000</div>
                <div class="line"></div>
                <div class="text">Тонн в год товарооборот компании</div>
            </div>
            <div class="col_3 item">
                <div class="number">2500</div>
                <div class="line"></div>
                <div class="text">Отечественных и иностранных производителей</div>
            </div>
            <div class="col_3 item">
                <div class="number">200</div>
                <div class="line"></div>
                <div class="text">Крупнейших дистрибьютеров и импортёров</div>
            </div>
            <div class="col_3 item">
                <div class="number">50000</div>
                <div class="line"></div>
                <div class="text">Тонн единовременного хранения продукции</div>
            </div>
            <div class="col_3 item">
                <div class="number">5000</div>
                <div class="line"></div>
                <div class="text">Тонн товара в сутки грузопоток территории</div>
            </div>
            <div class="col_3 item">
                <div class="number">65</div>
                <div class="line"></div>
                <div class="text">Регионов с регулярным присутствием</div>
            </div>
        </div>
    </div>
</section>
<section class="advantages_center">
    <span class="anchor" id="three"></span>
    <div class="wrap">
        <div class="edited_block">
            <h3>Преимущества нашего центра</h3>
            <p>Каждый день мы работаем над тем, чтобы предоставить вам максимально комфорты условия. В своей деятельности мы стремимся к тому, чтобы удовлетворить потребности всех групп покупателей и сделать процесс покупок и сотрудничества с нами максимально комфортным.</p>
        </div>
        <div class="grid grid--center">
            <div class="metro metro--new col_5">
                <div class="front">
                    <div class="box1">
                        <div class="ico">
                            <img src="<?= v::asset('images/ico_adve_1.png') ?>" alt="">
                        </div>
                        <strong>Развитая<br>инфраструктура</strong>
                        <span class="metro_btn">Подробнее</span>
                    </div>
                </div>
                <div class="back">
                    <div class="box2">
                        <strong>Развитая<br>инфраструктура</strong>
                        <p>На территории комплекса расположены банки, магазины, кафе столовые, ЕМЦ</p>
                        <span class="metro_btn">Назад</span>
                    </div>
                </div>
            </div>
            <div class="metro metro--new col_5">
                <div class="front">
                    <div class="box1">
                        <div class="ico">
                            <img src="<?= v::asset('images/ico_adve_2.png') ?>" alt="">
                        </div>
                        <strong>Передовые<br>технологии хранения</strong>
                        <span class="metro_btn">Подробнее</span>
                    </div>
                </div>
                <div class="back">
                    <div class="box2">
                        <strong>Передовые<br>технологии хранения</strong>
                        <p>Современное оборудование обеспечивает сохранение качества продукции </p>
                        <span class="metro_btn">Назад</span>
                    </div>
                </div>
            </div>
            <div class="metro metro--new col_5">
                <div class="front">
                    <div class="box1">
                        <div class="ico">
                            <img src="<?= v::asset('images/ico_adve_3.png') ?>" alt="">
                        </div>
                        <strong>Прямые поставки <br>от производителей</strong>
                        <span class="metro_btn">Подробнее</span>
                    </div>
                </div>
                <div class="back">
                    <div class="box2">
                        <strong>Прямые поставки от<br> производителей</strong>
                        <p>Позволяют нам предлагать продукцию по низким ценам</p>
                        <span class="metro_btn">Назад</span>
                    </div>
                </div>
            </div>
            <div class="metro metro--new col_5">
                <div class="front">
                    <div class="box1">
                        <div class="ico">
                            <img src="<?= v::asset('images/ico_adve_4.png') ?>" alt="">
                        </div>
                        <strong>Широкий ассортимент<br>по гибким ценам</strong>
                        <span class="metro_btn">Подробнее</span>
                    </div>
                </div>
                <div class="back">
                    <div class="box2">
                        <strong>Широкий ассортимент<br>по гибким ценам</strong>
                        <p>У нас каждый найдет именно ту продукцию, которая ему подходит</p>
                        <span class="metro_btn">Назад</span>
                    </div>
                </div>
            </div>
            <div class="metro metro--new col_5">
                <div class="front">
                    <div class="box1">
                        <div class="ico">
                            <img src="<?= v::asset('images/ico_adve_5.png') ?>" alt="">
                        </div>
                        <strong>Транспортная<br>доступность</strong>
                        <span class="metro_btn">Подробнее</span>
                    </div>
                </div>
                <div class="back">
                    <div class="box2">
                        <strong>Транспортная<br>доступность</strong>
                        <p>Мы находимся в 15 км от МКАД, федеральные трассы: 4 км от М4, 8 км от М2</p>
                        <span class="metro_btn">Назад</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="perspective">
    <span class="anchor" id="four"></span>
    <div class="wrap">
        <h3>Перспективы 2017 года</h3>
        <div class="block">
            <div class="top"><span>2017</span></div>
            <div class="middle">
                <p>Строительство и адаптация существующих складских помещений под новые товарные группы</p>
                <p>Создание таможенного поста и СВХ</p>
                <p>Создание собственной сети сбыта (ритейл)</p>
                <p>Создание системы аукционной торговли</p>
                <p>Создание интернет-магазина для оптоворозничной торговли</p>
            </div>
            <div class="bottom"><span>2018</span></div>
        </div>
    </div>
</section>
<? $APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"news_section",
	Array(
		"ACTIVE_DATE_FORMAT" => "j F Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array("", ""),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => \App\Iblock::NEWS_ID,
		"IBLOCK_TYPE" => \App\IblockType::CONTENT,
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => PHP_INT_MAX,
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => '',
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array("VIDEO_URL", ""),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC"
	)
); ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>