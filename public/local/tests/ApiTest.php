<?php

use App\Api;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase {
    /**
     * @dataProvider formResultsProvider
     */
    function testReduceFormResults($results, $expected) {
        $this->assertEquals(Api::reduceFormResults($results), $expected);
    }

    function formResultsProvider() {
        return array(
            array(
                array(
                    array('isSuccess' => true, 'errorMessageMaybe' => null),
                    array('isSuccess' => true, 'errorMessageMaybe' => null)
                ),
                array('isSuccess' => true, 'errorMessageMaybe' => null)
            ),
            array(
                array(
                    array('isSuccess' => false, 'errorMessageMaybe' => '1<br>'),
                    array('isSuccess' => true, 'errorMessageMaybe' => null)
                ),
                array('isSuccess' => false, 'errorMessageMaybe' => '1<br>')
            ),
            array(
                array(
                    array('isSuccess' => true, 'errorMessageMaybe' => null),
                    array('isSuccess' => false, 'errorMessageMaybe' => '1<br>')
                ),
                array('isSuccess' => false, 'errorMessageMaybe' => '1<br>')
            ),
            array(
                array(
                    array('isSuccess' => false, 'errorMessageMaybe' => '1<br>'),
                    array('isSuccess' => true, 'errorMessageMaybe' => null),
                    array('isSuccess' => false, 'errorMessageMaybe' => '2<br>')
                ),
                array('isSuccess' => false, 'errorMessageMaybe' => '1<br>2<br>')
            )
        );
    }
}
