<?php

// TODO what if some of the parts are missing?
$fullName = trim($USER->GetLastName().' '.$USER->GetFirstName().' '.$USER->GetSecondName());

$arResult['arUser']['FULL_NAME'] = $fullName;