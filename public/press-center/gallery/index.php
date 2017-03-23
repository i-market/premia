<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Фотогалерея");
?>

<div class="gallery">
    <div class="gallery-slider">
        <div class="slick">
            <? // TODO tmp ?>
            <img class="img" src="http://premia.englishfl.tmweb.ru/upload/iblock/5d7/img_1463.jpg" alt="">
            <img class="img" src="http://premia.englishfl.tmweb.ru/upload/iblock/5d7/img_1463.jpg" alt="">
        </div>
        <div class="name">TODO ssome albumsome albumsome albumome album</div>
    </div>
    <div class="albums">
        <? foreach (range(1, 6) as $num): ?>
            <div class="album" style="background-image: url('http://premia.englishfl.tmweb.ru/upload/iblock/5d7/img_1463.jpg');">
                <div class="name">TODO ssome albumsome albumsome albumome album</div>
            </div>
        <? endforeach ?>
    </div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>