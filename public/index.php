<?php
// TODO probably won't need this in prod env
date_default_timezone_set('Europe/Moscow');
require_once __DIR__.'/api.php';
?>
<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Краткое описание сайта для поисковика">
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>«Четыре сезона»</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800&amp;subset=cyrillic-ext" rel="stylesheet">
    <link rel="stylesheet" media="screen" href="css/lib/normalize.min.css">
    <link rel="stylesheet" media="screen" href="css/lib/slick.css">
    <link rel="stylesheet" media="screen" href="css/main.css">
    <!--[if gte IE 9]>
<style type="text/css">
.gradient {
filter: none;
}
</style>
<![endif]-->
    <!-- tmp fixes -->
    <style>
        .main_menu ul li {
            visibility: hidden;
        }
        .main_menu ul li.logo {
            visibility: visible;
        }
    </style>
</head>

<body data-spy="scroll" data-target="#navbar" id="one">
    <!--точки с прокруткой-->
    <div id="navbar">
        <ul class="nav">
            <li class="active">
                <a href="#one"></a>
            </li>
            <li>
                <a href="#two"></a>
            </li>
            <li>
                <a href="#three"></a>
            </li>
            <li>
                <a href="#four"></a>
            </li>
            <li>
                <a href="#five"></a>
            </li>
        </ul>
    </div>
    <!--гамбургер-->
    <div class="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <!--мобильное меню-->
    <div class="hidden_menu">
        <div class="top">
            <a href="#">+7 (495) 223-61-23</a>
        </div>
        <ul>
            <!--
            <li><a href="#"><span>О комплексе</span></a>
            </li>
            <li><a href="#"><span>Арендаторам</span></a>
            </li>
            <li><a href="#"><span>Покупателям</span></a>
            </li>
            <li><a href="#"><span>Контакты</span></a>
            </li>
            -->
            <li><a href="#"><span data-modal="re_call">Обратный звонок</span></a>
            </li>
            <li><a href="#"><span data-modal="rent">Форма на аренду</span></a>
            </li>
            <li><a href="#"><span data-modal="write_letter">Написать письмо</span></a>
            </li>
        </ul>
        <div class="bottom">
            <div class="social_icons">
                <a target="_blank" href="https://www.facebook.com/4seasons2013/" class="facebook"><i class="fa fa-facebook"></i></a>
                <a target="_blank" href="https://vk.com/4seasons_dom" class="vk"><i class="fa fa-vk"></i></a>
                <a target="_blank" href="https://www.instagram.com/4seasons_dom/" class="instagram"><i class="fa fa-instagram"></i></a>
            </div>
        </div>
    </div>
    <!--нижнее меню-->
    <div class="bottom_line">
        <div class="wrap">
            <span class="write_letter" data-modal="write_letter">написать письмо</span>
            <span class="btn" data-modal="re_call">заказать звонок</span>
            <span class="form_order" data-modal="rent">Запросить форму на аренду</span>
        </div>
    </div>
    <!--HEADER-->
    <header class="header">
        <div class="wrap">
            <div class="opacity_bars"></div>
            <a class="logo_mobile" href="#">
                <img src="images/logo.png" alt="">
            </a>
            <div class="left">
                <div class="social_icons">
                    <a target="_blank" href="https://www.facebook.com/4seasons2013/" class="facebook"><i class="fa fa-facebook"></i></a>
                    <a target="_blank" href="https://vk.com/4seasons_dom" class="vk"><i class="fa fa-vk"></i></a>
                    <a target="_blank" href="https://www.instagram.com/4seasons_dom/" class="instagram"><i class="fa fa-instagram"></i></a>
                </div>
                <a href="#five" class="scheme btn">схема проезда</a>
            </div>
            <nav class="main_menu cf">
                <ul>
                    <li><a href="#">о комплексе</a>
                    </li>
                    <li><a href="#" class="active">Арендаторам</a>
                    </li>
                    <li class="logo">
                        <a href="#"></a>
                    </li>
                    <li><a href="#">Покупателям</a>
                    </li>
                    <li><a href="#">Контакты</a>
                    </li>
                </ul>
            </nav>
            <div class="right">
                <p class="tel"><a href="tel:+7 (495) 223-61-23">+7 (495) 223-61-23</a>
                </p>
                <div class="lang_block">
                    <div class="flag">
                        <img src="images/lang.png" alt="">
                    </div>
                    <div class="blcok_choose_lang">
                        <span class="lang">RUS</span>
                        <div class="dd_choose_lang">
                            <a href="#">
                                <img src="images/az.png" alt="">Az</a>
                            <a href="#">
                                <img src="images/eng.png" alt="">Eng</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- CONTENT -->
    <main class="content">
        <section class="wrap_slider">
            <div class="wrap_arrows">
                <span class="arrows prev"></span>
                <span class="arrows next"></span>
            </div>
            <div class="slider">
                <div class="slide" style="background: url('images/pic_1.jpg')no-repeat center center">
                    <div class="block">
                        <h1>Арендные каникулы</h1>
                        <p class="next_h1">Снижение ставки</p>
                        <h2>Скидка c м² <span class="orange">−20 P</span> в сутки</h2>
                        <p class="next_h2">На складские помещения и офисы</p>
                    </div>
                </div>
                <div class="slide" style="background: url('images/upload/slider-2.jpg')no-repeat center center">
                    <div class="block">
                        <h1>Четыре Сезона</h1>
                        <p class="next_h1">г.Домодедово</p>
                        <h2>Всего <span class="orange">15 км</span> от МКАД</h2>
                        <p class="next_h2">4 км от М4 "Дон" | 8 км от М2 "Крым"</p>
                    </div>
                </div>
                <div class="slide" style="background: url('images/upload/slider-3.jpg')no-repeat center center">
                    <div class="block">
                        <h1>Склады</h1>
                        <p class="next_h1">фрукты | овощи | ягоды</p>
                        <h2>Режим t <span class="orange">+2...+8 °С</span></h2>
                        <p class="next_h2">Оптимальные условия аренды</p>
                    </div>
                </div>
            </div>
            <div class="dots"></div>
        </section>
        <section class="rental_offers">
            <span class="achor achor--two" id="two"></span>
            <div class="wrap">
                <div class="top">
                    <p class="big_text">70 000 м²</p>
                    <div class="block">
                        <h3>предложения по аренде</h3>
                        <p>ОРЦ "Четыре сезона" предлагает в аренду складские корпуса с постоянным температурным режимом и удобные помещения под офисы</p>
                    </div>
                </div>
                <div class="grid">
                    <div class="item col_3">
                        <div class="img">
                            <img src="images/pic_2.jpg" alt="">
                            <p class="text">Павильоны 25 500 м²</p>
                        </div>
                        <div class="info">
                            <p>
                                <span>Арендуемая площадь</span>
                                <span>— от 150 до 600 м²</span>
                            </p>
                            <p>
                                <span>Температурный режим</span>
                                <span>— от +4 до +8 °С</span>
                            </p>
                            <p>
                                <span>Высота потолков</span>
                                <span>— 10 м</span>
                            </p>
                            <p>
                                <span>Нагрузка на пол</span>
                                <span>— 600 кг/м²</span>
                            </p>
                            <p>
                                <span>Строение</span>
                                <span>— №2, №3</span>
                            </p>
                        </div>
                        <div class="bottom">
                            <span class="btn btn--fiolet" data-modal="rental_offers">Показать схему</span>
                        </div>
                    </div>
                    <div class="item col_3">
                        <div class="img">
                            <img src="images/upload/sklad-2.png" alt="">
                            <p class="text">Павильоны 14 700 м²</p>
                        </div>
                        <div class="info">
                            <p>
                                <span>Арендуемая площадь</span>
                                <span>— от 256 до 512 м²</span>
                            </p>
                            <p>
                                <span>Температурный режим</span>
                                <span>— от 2 до +12 °С</span>
                            </p>
                            <p>
                                <span>Высота потолков</span>
                                <span>— 6 м</span>
                            </p>
                            <p>
                                <span>Нагрузка на пол</span>
                                <span>— 600 кг/м²</span>
                            </p>
                            <p>
                                <span>Строение</span>
                                <span>— №11, №12, №13</span>
                            </p>
                        </div>
                        <div class="bottom">
                            <span class="btn btn--green" data-modal="rental_offers_2">Показать схему</span>
                        </div>
                    </div>
                    <div class="item col_3">
                        <div class="img">
                            <img src="images/upload/sklad-3.png" alt="">
                            <p class="text">Офисы 2 700 м²</p>
                        </div>
                        <div class="info">
                            <p>
                                <span>Арендуемая площадь</span>
                                <span>— от 22 м²</span>
                            </p>
                            <p>
                                <span>Этажность</span>
                                <span>— 3/3</span>
                            </p>
                            <p>
                                <span>Планировка</span>
                                <span>— коридорно-кабинетная</span>
                            </p>
                            <p>
                                <span>Кондиционирование</span>
                                <span>— центральное</span>
                            </p>
                            <p>
                                <span>Отопление</span>
                                <span>— центральное</span>
                            </p>
                            <p>
                                <span>Охрана</span>
                                <span>— ЧОП, круглосуточно</span>
                            </p>
                        </div>
                        <div class="bottom">
                            <span class="btn btn--red" data-modal="rental_offers_3">Показать схему</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="service">
            <span class="achor achor--three" id="three"></span>
            <div class="wrap">
                <h3>Услуги</h3>
                <strong>ОРЦ "Четыре сезона" предлагает широкий спектр услуг с использованием<br> высокотехнологичного оборудования</strong>
                <div class="grid">
                    <div class="item col_6">
                        <div class="ico">
                            <img src="images/ico_1.png" alt="">
                            <div class="hidden">
                                <img src="images/ico_11.png" alt="">
                            </div>
                        </div>
                        <div class="text">Хранение</div>
                    </div>
                    <div class="item col_6">
                        <div class="ico">
                            <img src="images/ico_2.png" alt="">
                            <div class="hidden">
                                <img src="images/ico_22.png" alt="">
                            </div>
                        </div>
                        <div class="text">Перевалка</div>
                    </div>
                    <div class="item col_6">
                        <div class="ico">
                            <img src="images/ico_3.png" alt="">
                            <div class="hidden">
                                <img src="images/ico_33.png" alt="">
                            </div>
                        </div>
                        <div class="text">Парк погрузочной техники</div>
                    </div>
                    <div class="item col_6">
                        <div class="ico">
                            <img src="images/ico_4.png" alt="">
                            <div class="hidden">
                                <img src="images/ico_44.png" alt="">
                            </div>
                        </div>
                        <div class="text">Распределение крупных партий</div>
                    </div>
                    <div class="item col_6">
                        <div class="ico">
                            <img src="images/ico_5.png" alt="">
                            <div class="hidden">
                                <img src="images/ico_55.png" alt="">
                            </div>
                        </div>
                        <div class="text">Крупнооптовая торговля</div>
                    </div>
                    <div class="item col_6">
                        <div class="ico">
                            <img src="images/ico_6.png" alt="">
                            <div class="hidden">
                                <img src="images/ico_66.png" alt="">
                            </div>
                        </div>
                        <div class="text">Мелкооптовая торговля</div>
                    </div>
                </div>
            </div>
        </section>
        <section class="advantages">
            <span class="achor achor--four" id="four"></span>
            <div class="wrap">
                <h3>наши преимущества</h3>
                <div class="block">
                    <div class="item">
                        <div class="ico">
                            <img src="images/ico_7.png" alt="">
                            <div class="hidden">
                                <img src="images/ico_77.png" alt="">
                            </div>
                        </div>
                        <p class="text">Высокая транспортная доступность</p>
                        <p class="info">Находимся в непосредственной близости к трассам федерального значения и маршрутам поставки грузов</p>
                        <div class="bottom">
                            <div class="block">
                                <p><span>4</span> км до трассы М2</p>
                                <p><span>8</span> км до трассы М4</p>
                                <p><span>15</span> км до МКАД</p>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="ico">
                            <img src="images/ico_8.png" alt="">
                            <div class="hidden">
                                <img src="images/ico_88.png" alt="">
                            </div>
                        </div>
                        <p class="text">Современные технологии</p>
                        <p class="info">Используем новейшее оборудование для обеспечения работы всех складских систем</p>
                        <div class="bottom">
                            <div class="block">
                                <p>передовые логистические решения</p>
                                <p>высокая степень автоматизации</p>
                                <p>контроль температурного режима</p>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="ico">
                            <img src="images/ico_9.png" alt="">
                            <div class="hidden">
                                <img src="images/ico_99.png" alt="">
                            </div>
                        </div>
                        <p class="text">Автономная инфраструктура</p>
                        <p class="info">На территории комплекса созданы все необходимые условия для сотрудников и клиентов</p>
                        <div class="bottom">
                            <div class="block">
                                <p>Единый Миграционный Центр</p>
                                <p>кафе и столовые</p>
                                <p>магазины и банки</p>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="ico">
                            <img src="images/ico_10.png" alt="">
                            <div class="hidden">
                                <img src="images/ico_100.png" alt="">
                            </div>
                        </div>
                        <p class="text">Безопасность комплекса</p>
                        <p class="info">На территории центра круглосуточно гарантированы безопасность и охрана порядка</p>
                        <div class="bottom">
                            <div class="block">
                                <p>пропускной режим</p>
                                <p>непрерывное видеонаблюдение</p>
                                <p>группа быстрого реагирования</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="map" id="five">
            <div id="contacts-map" class="map-container"></div>
            <div class="buble">
                <strong>наш адрес</strong>
                <p class="plane">г. Домодедово, мкр. Северный, ул. Логистическая, участок 9</p>
                <p class="marker">GPS: 55.470993, 37.712681</p>
            </div>
        </section>
    </main>
    <!-- FOOTER -->
    <footer class="footer">
        <p><?= date('Y') ?> © Четыре-сезона.рф. Создание и продвижение сайта: <a href="https://i-market.ru/" rel="nofollow" target="_blank">i-Market</a>
        </p>
    </footer>
    <!--Модалки-->
    <!--Обратный звонок-->
    <div class="modal modal_window" id="re_call">
        <form class="block" method="post" action="" name="">
            <input name="fn" type="hidden" value="callback-request">
            <span class="close"></span>
            <strong>Заказать звонок</strong>
            <input name="name" type="text" placeholder="ФИО">
            <input name="phone" type="text" placeholder="Телефон">
            <textarea name="message" placeholder="Сообщение"></textarea>
            <div class="bottom">
                <button type="submit" class="btn">отправить</button>
            </div>
        </form>
    </div>
    <!--Написать письмо-->
    <div class="modal modal_window" id="write_letter">
        <form class="block" method="post" action="" name="">
            <input name="fn" type="hidden" value="message">
            <span class="close"></span>
            <strong>Написать письмо</strong>
            <input name="name" type="text" placeholder="ФИО">
            <input name="email" type="text" placeholder="Почта">
            <textarea name="message" placeholder="Сообщение"></textarea>
            <div class="bottom">
                <button type="submit" class="btn">отправить</button>
            </div>
        </form>
    </div>
    <!--Форма аренды-->
    <div class="modal modal_window" id="rent">
        <form class="block" method="post" action="" name="">
            <input name="fn" type="hidden" value="rent">
            <span class="close"></span>
            <strong>Написать письмо</strong>
            <input name="name" type="text" placeholder="ФИО">
            <input name="phone" type="text" placeholder="Телефон">
            <input name="email" type="text" placeholder="Почта">
            <select name="type" id="">
                <option value="Не выбран">Тип помещения</option>
                <option value="Офис">Офис</option>
                <option value="Склад">Склад</option>
            </select>
            <input name="space-needed" type="text" placeholder="Интересующая площадь в м²">
            <!--<div class="people_test">
                <img src="images/robot.png" alt="">
            </div>-->
            <div class="bottom">
                <button type="submit" class="btn">отправить</button>
            </div>
        </form>
    </div>
    <!--слайдеры с планом-->
    <div class="modal modal_rent" id="rental_offers">
        <div class="block">
            <span class="close"></span>
            <div class="slider_rent">
                <div class="slide">
                    <strong></strong>
                    <div class="img">
                        <img src="images/upload/floor-plans/1-1.png" alt="">
                    </div>
                </div>
                <div class="slide">
                    <strong></strong>
                    <div class="img">
                        <img src="images/upload/floor-plans/1-2.png" alt="">
                    </div>
                </div>
            </div>
            <div class="bottom">
                <div class="wrap_arrows">
                    <span class="arrows prev"></span>
                    <span class="arrows next"></span>
                </div>
                <div class="numbers">
                    <span class="current">1</span> /
                    <span class="all"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal_rent" id="rental_offers_2">
        <div class="block">
            <span class="close"></span>
            <div class="slider_rent">
                <div class="slide">
                    <strong></strong>
                    <div class="img">
                        <img src="images/upload/floor-plans/2-1.png" alt="">
                    </div>
                </div>
                <div class="slide">
                    <strong></strong>
                    <div class="img">
                        <img src="images/upload/floor-plans/2-2.png" alt="">
                    </div>
                </div>
                <div class="slide">
                    <strong></strong>
                    <div class="img">
                        <img src="images/upload/floor-plans/2-3.png" alt="">
                    </div>
                </div>
                <div class="slide">
                    <strong></strong>
                    <div class="img">
                        <img src="images/upload/floor-plans/2-4.png" alt="">
                    </div>
                </div>
            </div>
            <div class="bottom">
                <div class="wrap_arrows">
                    <span class="arrows prev"></span>
                    <span class="arrows next"></span>
                </div>
                <div class="numbers">
                    <span class="current">1</span> /
                    <span class="all"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal_rent" id="rental_offers_3">
        <div class="block">
            <span class="close"></span>
            <div class="slider_rent">
                <div class="slide">
                    <strong></strong>
                    <div class="img">
                        <img src="images/upload/floor-plans/offices-1.png" alt="">
                    </div>
                </div>
                <div class="slide">
                    <strong></strong>
                    <div class="img">
                        <img src="images/upload/floor-plans/offices-2.png" alt="">
                    </div>
                </div>
            </div>
            <div class="bottom">
                <div class="wrap_arrows">
                    <span class="arrows prev"></span>
                    <span class="arrows next"></span>
                </div>
                <div class="numbers">
                    <span class="current">1</span> /
                    <span class="all"></span>
                </div>
            </div>
        </div>
    </div>
    <!-- ПОДКЛЮЧЕНИЕ СКРИПТОВ -->
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://use.fontawesome.com/d5e5cdfb8c.js"></script>
    <script type="text/javascript" src="js/vendor/scroll.js"></script>
    <script type="text/javascript" src="js/vendor/slick.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/main.js"></script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDYQPq92PcAvbnyl9H7MqeQ86w_4FfsCaU&callback=App.googleMapsCallback">
    </script>

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter41645344 = new Ya.Metrika({
                        id:41645344,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true,
                        webvisor:true
                    });
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/41645344" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-45348215-53', 'auto');
        ga('send', 'pageview');

    </script>
</body>

</html>