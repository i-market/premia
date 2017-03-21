<?php

use Core\View as v;

$arResult['ITEMS'] = v::assocResized($arResult['ITEMS'], 'PREVIEW_PICTURE', array(
    'width' => 130 * 1.5, 'height' => 130 * 1.5
));
