<?php

// https://github.com/cjp2600/bim-core/blob/bf4f8c216d76c0ca4b5c638d01560fcfb64c83dc/src/Export/Dump/dump.php

function haveTime() {
    return true;
}

function IntOption($name, $def = 0) {
    static $CACHE;
    if (!$CACHE[$name]) {
        $CACHE[$name] = COption::GetOptionInt('main', $name, $def);
    }
    return $CACHE[$name];
}

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php';
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/backup.php';
