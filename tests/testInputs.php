<?php
/**
 * Created by PhpStorm.
 * User: Praveen
 * Date: 5/18/2017
 * Time: 12:04 PM
 */

use PHPUnit\Framework\TestCase;

final class testInputs extends TestCase
{
    public function testLogin()
    {
        $this->visit('/login')
            ->type('manager@gmail.com', 'email')
            ->type('123456','password')
            ->check('remember')
            ->press('signIn')
            ->seePageIs('/projectManagerHome');
    }
}