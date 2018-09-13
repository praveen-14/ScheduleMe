<?php


abstract class AddContent extends \Illuminate\Foundation\Testing\TestCase
{
    public function testProjectManager(){
        $this->visit('/login');
        $this->type('manager@gmail.com','email');
        $this->type('123456','email');
        $this->press('signIn');
    }
}