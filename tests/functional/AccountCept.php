<?php

/**
 * @var \Codeception\Scenario $scenario
 */
$I = new FunctionalTester($scenario);
$I->wantTo('Test Account with Bot (Requires Login');

$I->amOnPage('/user/login');
$I->fillField('#formLogin input[name=email]', 'test@test.com');
$I->fillField('#formLogin input[name=password]', 'efixir');
$I->submit('#formLogin');

$I->see('Dashboard');
$I->seeCurrentUrlEquals('/dashboard');

$I->amOnPage('/dashboard/account');
$I->see('Account');
$I->seeCurrentUrlEquals('/dashboard/account');
