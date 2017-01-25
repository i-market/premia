<?php

use Hendrix\NewsListLike;
use Hendrix\Underscore as _;

$template = $this->getComponent()->getTemplate();
$arResult['ITEMS'] = array_map(function($item) use ($template) {
    return _::set($item, 'BX_ID', NewsListLike::addEditingActions($item, $template));
}, $arResult['ITEMS']);