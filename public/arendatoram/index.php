<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Арендаторам");
?>

<?= \App\View::twig()->render('/arendatoram/index.twig', array(
    'rental_offers' => array('korpus-4.jpg', '1-1.png', '1-2.png'),
    'rental_offers_2' => array('korpus-11.jpg', 'korpus-12.jpg', 'korpus-13.jpg'),
    'rental_offers_3' => array('offices-1.jpg', 'offices-2.jpg', 'abk.jpg', '2-floor-abk.jpg')
)) ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>