<?php
/**
 * @var \Codeception\Scenario $scenario
 */
$I = new FunctionalTester($scenario);
$I->wantTo('Test Login with bot');

$I->amOnPage('/login');
$I->fillField('.form-login input[name=email]', 'test@test.com');
$I->fillField('.form-login input[name=password]', 'efixir');
$I->submit('.form-login');

$I->see('Dashboard');
$I->seeCurrentUrlEquals('/dashboard');
