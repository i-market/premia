<?php

use App\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase {
    /**
     * @dataProvider fullNameProvider
     */
    function testParseFullName($fullName, $expected) {
        $this->assertEquals(User::parseFullName($fullName), $expected);
    }
    
    function fullNameProvider() {
        return array(
            array('  ', array()),
            array(' Иванов', array(
                'FULL_NAME' => 'Иванов',
                'LAST_NAME' => 'Иванов'
            )),
            array('Иванов Иван', array(
                'FULL_NAME' => 'Иванов Иван',
                'LAST_NAME' => 'Иванов',
                'FIRST_NAME' => 'Иван'
            )),
            array('Иванов Иван Иванович', array(
                'FULL_NAME' => 'Иванов Иван Иванович',
                'LAST_NAME' => 'Иванов',
                'FIRST_NAME' => 'Иван',
                'PATRONYMIC' => 'Иванович'
            )),
            array('Иванов Иван Иванович Иванович', array(
                'FULL_NAME' => 'Иванов Иван Иванович Иванович'
            ))
        );
    }
}
