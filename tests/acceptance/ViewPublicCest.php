<?php


class ViewPublicCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // Tests, Ensure the Public Pages Work
    public function homeTest(AcceptanceTester $I)
    {
        // Home Page
        $I->amOnPage('/');
        $I->see('Programming Courses');
    }

    public function productTest(AcceptanceTester $I)
    {
        // Check Breadcrumbs
        $I->amOnPage('/products');
        $I->see('Products', '.active');

        $I->amOnPage('/products/view/php-punch-in-the-face');
        $I->see('PHP Punch in the Face', '.active');

        $I->amOnPage('/products/preview/php-punch-in-the-face/1');
        $I->see('1.1 Install Php', '.active');
    }

    public function singlePageTest(AcceptanceTester $I)
    {
        $I->amOnPage('/contact');
        $I->see('Contact', '.active');

        $I->amOnPage('/updates');
        $I->see('Updates', '.active');

        $I->amOnPage('/terms');
        $I->see('Terms and Privacy', '.active');

        $I->amOnPage('/lab');
        $I->see('Lab', '.active');

        $I->amOnPage('/lab');
        $I->see('Lab', '.active');
    }
}
