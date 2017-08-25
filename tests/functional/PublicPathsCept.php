<?php
/**
 * @var \Codeception\Scenario $scenario
 */

$I = new FunctionalTester($scenario);

// Tests, Ensure the Public Pages Work
$I->amOnPage('/');
$I->see('Programming Courses');

// Check Titles
$I->amOnPage('/product');
$I->seeInTitle('Product');

$I->amOnPage('/product/course/php-punch-in-the-face');
$I->seeInTitle('PHP Punch');

$I->amOnPage('/product/course/preview/php-punch-in-the-face/1');
$I->seeInTitle('1.1');

$I->amOnPage('/contact');
$I->seeInTitle('Contact');

$I->amOnPage('/updates');
$I->seeInTitle('Update');

$I->amOnPage('/terms');
$I->seeInTitle('Terms');

$I->amOnPage('/lab');
$I->seeInTitle('Lab');

$I->amOnPage('/promotion');
$I->seeInTitle('Promotion');

$I->amOnPage('/user/login');
$I->seeInTitle('Login');

$I->amOnPage('/user/register');
$I->seeInTitle('Register');

