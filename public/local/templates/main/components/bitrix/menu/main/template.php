<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<nav class="main_menu">
    <ul class="wrap">
        <? $previousLevel = 0; ?>
        <? foreach ($arResult as $item): ?>
            <?
            $class = $item['SELECTED'] ? 'active' : '';
            if ($previousLevel && $item['DEPTH_LEVEL'] < $previousLevel) {
                echo str_repeat('</ul></li>', ($previousLevel - $item['DEPTH_LEVEL']));
            }
            ?>
            <? if ($item['IS_PARENT']): ?>
                <li class="<?= $class ?>">
                    <a href="<?= $item['LINK'] ?>"><?= $item['TEXT'] ?></a>
                    <ul>
            <? else: ?>
                <li class="<?= $class ?>">
                    <a href="<?= $item['LINK'] ?>"><?= $item['TEXT'] ?></a>
                </li>
            <? endif ?>
            <? $previousLevel = $item['DEPTH_LEVEL'] ?>
        <? endforeach ?>
        <?
        if ($previousLevel > 1) {
            echo str_repeat('</ul></li>', ($previousLevel - 1));
        }
        ?>
    </ul>
</nav>
