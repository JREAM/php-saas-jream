<?php

namespace Admin;

use \Phalcon\Tag;

class AdminController extends \BaseController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('Admin | ' . $this->di['config']['title']);
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->pick("admin/admin");
    }

    // --------------------------------------------------------------

    public function migrateListAction()
    {
        // JREAM accounts
        $users = User::find([
            'email IS NOT NULL'
        ]);
        foreach ($users as $user) {
            $this->_insertNewsletterSubscribers($user, $email);
        }

        // FB Accounts
        $users = User::find([
            'facebook_email IS NOT NULL'
        ]);
        foreach ($users as $user) {
            $this->_insertNewsletterSubscribers($user, $email);
        }
    }

    // --------------------------------------------------------------

    private function _insertNewsletterSubscribers($user, $email)
    {
        $newsletterSubscription = new \NewsletterSubscription;
        $newsletterSubscription->user_id = $user->id;
        $newsletterSubscription->email = $email;
        $newsletterSubscription->save();
    }

    // --------------------------------------------------------------

    public function sendAwsEmail()
    {
        // SES client, Get Email, Get Newsletter List
        $content = $this->component->email->create('newsletter', [
            'title'         => $title,
            'content'       => $content,
            'links'         => $links,
            'unsubscribe'   => 'unsubscribe'
        ]);

        // Newsletter to use for content
        $newsletter = \Newsletter:findById(1);
        $newsletter->subject;
        $newsletter->body;

        // Parse any markdown code to HTML
        $parsedown = new \Parsedown();
        $content = $parsedown->parse($content);

        // @TODO: This must go through aws SES.
        $mail_result = $this->di->get('email', [
            [
                'to_name'    => '@@THE USERS NAME if available (USER has child Newsletter)@@',
                'to_email'   => '@@THE USERS EMAIL ADDRESS@@',
                'from_name'  => $this->config->email->from_name,
                'from_email' => $this->config->email->from_address,
                'subject'    => "JREAM Promotion 70% off Courses 1 Week",
                'content'    => $content,
            ],
        ]);

        $newsletterResult = new \NewsletterResult();
        $newsletterResult->newsletter_id = getDateTime();
        $newsletterResult->created_at = getDateTime();
        $newsletterResult->is_sent = 1;
        $newsletterResult->result = $mail_result;
        $newsletterResult->save();

    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------
