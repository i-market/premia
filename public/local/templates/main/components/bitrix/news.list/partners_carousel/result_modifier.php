<?php

use Core\View as v;

$arResult['ITEMS'] = v::assocResized($arResult['ITEMS'], 'PREVIEW_PICTURE', array('width' => 190, 'height' => 60));
