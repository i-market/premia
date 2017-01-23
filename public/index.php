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
        <div class="slide slide--inner slide--opacity" style="background: url('<?= v::asset('images/slider3.png') ?>')no-repeat center center">
            <div class="block">
                <h1>Четыре сезона</h1>
                <span class="line"></span>
                <h2>Оптово-распределительный центр</h2>
            </div>
        </div>
        <div class="slide slide--inner slide--opacity" style="background: url('<?= v::asset('images/slider3.png') ?>')no-repeat center center">
            <div class="block">
                <h1>Четыре сезона</h1>
                <span class="line"></span>
                <h2>Оптово-распределительный центр</h2>
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
            <p>Центр решает задачи централизации услуг по хранению, предпродажной подготовке, распределению продукции во все регионы России. В его основе заложены технологии мировых лидеров в сфере товарного распределения, адаптированные под российские стандарты.</p>
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
            <h3>Преимущества нашего центра...</h3>
            <p>У нас работают только профессионалы, имеющие высшее образование и опыт работы. Наши сотрудники посещают курсы повышения квалификации и семинары, осваивают новые программы и направления. Наш центр всегда в курсе всего нового, что появляется в области сельскохозяйственной продукции. </p>
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
                        <strong>Цены и ассортимент</strong>
                        <p>Российская специфика, как следует из вышесказанного, все еще интересна для многих.
                            <br>Баинг и селлинг концентрирует потребительский формирование имиджа.</p>
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
                        <strong>Цены и ассортимент</strong>
                        <p>Российская специфика, как следует из вышесказанного, все еще интересна для многих.
                            <br>Баинг и селлинг концентрирует потребительский формирование имиджа.</p>
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
                        <strong>Цены и ассортимент</strong>
                        <p>Российская специфика, как следует из вышесказанного, все еще интересна для многих.
                            <br>Баинг и селлинг концентрирует потребительский формирование имиджа.</p>
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
                        <strong>Цены и ассортимент</strong>
                        <p>Российская специфика, как следует из вышесказанного, все еще интересна для многих.
                            <br>Баинг и селлинг концентрирует потребительский формирование имиджа.</p>
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
                <p>Создание интернет магазина для оптоворозничной торговли</p>
            </div>
            <div class="bottom"><span>2018</span></div>
        </div>
    </div>
</section>
<section class="news">
    <span class="anchor" id="five"></span>
    <div class="wrap">
        <h3>События</h3>
        <div class="wrap_news">
            <div class="left">
                <div class="grid">
                    <div class="col_3 news_item" data-modal="news_modal">
                        <div class="img video">
                            <img src="<?= v::asset('images/pic_8.jpg') ?>" alt="">
                            <div class="date">
                                <span>28 января</span>
                            </div>
                        </div>
                        <div class="title">В Подмосковье создадут Федеральный научный центр</div>
                        <div class="line"></div>
                        <div class="text">В Московской области, недалеко от столицы России, планируется организовать Федеральный научный центр по кормопроизводству, агроэкологии и </div>
                    </div>
                    <div class="col_3 news_item hidden_second">
                        <div class="img foto">
                            <img src="<?= v::asset('images/pic_8.jpg') ?>" alt="">
                            <div class="date">
                                <span>28 января</span>
                            </div>
                        </div>
                        <div class="title">В Подмосковье создадут Федеральный научный центр</div>
                        <div class="line"></div>
                        <div class="text">В Московской области, недалеко от столицы России, планируется организовать Федеральный научный центр по кормопроизводству, агроэкологии и </div>
                    </div>
                    <div class="col_3 news_item hidden_first">
                        <div class="img foto">
                            <img src="<?= v::asset('images/pic_8.jpg') ?>" alt="">
                            <div class="date">
                                <span>28 января</span>
                            </div>
                        </div>
                        <div class="title">В Подмосковье создадут Федеральный научный центр</div>
                        <div class="line"></div>
                        <div class="text">В Московской области, недалеко от столицы России, планируется организовать Федеральный научный центр по кормопроизводству, агроэкологии и </div>
                    </div>
                </div>
            </div>
            <div class="right">
                <div class="wrap_news_slider">
                    <span class="next"></span>
                    <span class="prev"></span>
                    <div class="news_slider">
                        <div class="news_item">
                            <div class="img foto">
                                <img src="<?= v::asset('images/pic_8.jpg') ?>" alt="">
                                <div class="date">
                                    <span>28 января</span>
                                </div>
                            </div>
                            <div class="title">В Подмосковье создадут Федеральный научный центр</div>
                            <div class="line"></div>
                            <div class="text">В Московской области, недалеко от столицы России, планируется организовать Федеральный научный центр по кормопроизводству, агроэкологии и </div>
                        </div>
                        <div class="news_item">
                            <div class="img foto">
                                <img src="<?= v::asset('images/pic_8.jpg') ?>" alt="">
                                <div class="date">
                                    <span>28 января</span>
                                </div>
                            </div>
                            <div class="title">В Подмосковье создадут Федеральный научный центр</div>
                            <div class="line"></div>
                            <div class="text">В Московской области, недалеко от столицы России, планируется организовать Федеральный научный центр по кормопроизводству, агроэкологии и </div>
                        </div>
                        <div class="news_item">
                            <div class="img foto">
                                <img src="<?= v::asset('images/pic_8.jpg') ?>" alt="">
                                <div class="date">
                                    <span>28 января</span>
                                </div>
                            </div>
                            <div class="title">В Подмосковье создадут Федеральный научный центр</div>
                            <div class="line"></div>
                            <div class="text">В Московской области, недалеко от столицы России, планируется организовать Федеральный научный центр по кормопроизводству, агроэкологии и </div>
                        </div>
                        <div class="news_item">
                            <div class="img foto">
                                <img src="<?= v::asset('images/pic_8.jpg') ?>" alt="">
                                <div class="date">
                                    <span>28 января</span>
                                </div>
                            </div>
                            <div class="title">В Подмосковье создадут Федеральный научный центр</div>
                            <div class="line"></div>
                            <div class="text">В Московской области, недалеко от столицы России, планируется организовать Федеральный научный центр по кормопроизводству, агроэкологии и </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>