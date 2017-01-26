<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Арендаторам");
?>

<?= \App\View::twig()->render('/arendatoram/index.twig', array(
    'rental_offers' => array(
        array(
            'path' => 'korpus-2.jpg',
            'caption' => 'Корпус №2'
        ),
        array(
            'path' => 'korpus-3.jpg',
            'caption' => 'Корпус №3'
        ),
        array(
            'path' => 'korpus-4.jpg',
            'caption' => 'Корпус №4'
        )
    ),
    'rental_offers_2' => array(
        array(
            'path' => 'korpus-11.jpg',
            'caption' => 'Корпус №11'
        ),
        array(
            'path' => 'korpus-12.jpg',
            'caption' => 'Корпус №12'
        ),
        array(
            'path' => 'korpus-13.jpg',
            'caption' => 'Корпус №13'
        )
    ),
    'rental_offers_3' => array(
        array(
            'path' => 'offices-1.jpg',
            'caption' => 'Корпус №2 офисы'
        ),
        array(
            'path' => 'offices-2.jpg',
            'caption' => 'Корпус №3 офисы'
        ),
        array(
            'path' => 'abk.jpg',
            'caption' => 'Административно-бытовой корпус (1 этаж)'
        ),
        array(
            'path' => '2-floor-abk.jpg',
            'caption' => 'Административно-бытовой корпус (2 этаж)'
        )
    )
)) ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>