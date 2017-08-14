<?php


class ApiController extends \BaseController
{

    /**
     * ApiController constructor.
     */
    public function __construct() {
        parent::__construct();
        // From the config/services.php We can use our custom Cookie Wrapper
        $this->components->cookie;
    }

    // --------------------------------------------------------------

    /**
     * Renders a markdown preview
     *
     * @return  string  json
     */
    public function markdownAction()
    {
        if (!$this->session->has('id')) {
            throw new \DomainException('Only Logged in users can do this.');
        }

        $parsedown = new \Parsedown();
        $content = trim($this->request->getPost('content'));
        if ($content) {
            $content = $parsedown->parse($content);
        }

        $this->output(1, $content);
    }

    // --------------------------------------------------------------

    /**
     * Googles ReCaptcha Verification
     *
     * @return mixed (JSON)
     */
    public function recaptchaAction()
    {
        if ($this->session->has('recaptcha') && $this->session->get('recaptcha')) {
            $this->output(1, 'Recaptcha already approved');
            return false;
        }


        if (\STAGE == 'local') {
            $this->session->set('recaptcha', 1);
            $this->output(1, 'Local Development Auto-Pass');
            return false;
        }

        // Verify Recaptcha
        $recaptcha = $this->request->getPost('g-recaptcha-response');

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_POST => 1,
            CURLOPT_URL => "https://www.google.com/recaptcha/api/siteverify",
            CURLOPT_POSTFIELDS => '',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => sprintf("secret=%s&response=%s",
                getenv('GOOGLE_RECAPTCHA_SECRET'),
                $recaptcha
            ),
        ]);
        $response = json_decode(curl_exec($ch));
        curl_close($ch);

        $result = (bool) $response->success;

        // Set a session so they don't try to work-around it..
        $this->session->set('recaptcha', $result);
        $this->output($result, 'Invalid Recaptcha');
        return false;
    }

    // --------------------------------------------------------------

    /**
     * @param bool $product_id
     *
     * @return mixed
     */
    public function checkPromotionAction($product_id = false)
    {
        if (! $promotion_code) {
            return false;
        }

        $promo = \Promotion::check( (string) $promotion_code, (int) $product_id);
        if (!$promo) {
            $this->output(0, 'Invalid Promotion');
            return false;
        }

        // The wrong userID has no acccess, he won't get a cookie.
        if ($promo->user_id && $this->session->get('id') != $promo->user_id) {
            $this->output(0, 'This promotion is for an individual only. If that is you, please login to apply the promotion code.');
            return false;
        }

        // NOTE: For product_id it is checked DURING checkout.

        // Save Cookie incase they are logged in or out,
        // Use this data to check the real promo (Check the expired date and all).
        $this->components->cookie->set('promotion', json_encode($promo));

        // For the output only.
        // expires_at & deleted_at are checked DURING the checkout
        $data = [
            'expires_at' => $data->expires_at
        ];

        if ($promo->product_id) {
            $data['product_id'] = $promo->product_id;
        }

        // Only one of these apply
        if ($promo->percent_off) {
            // Ensure this is a decimal, so 25 = .25
            if (! $promo->percent_off >= 0) {
                $promo->percent_off = ($promo->percent_off / 100);
            }

            $data['percent_off'] = $promo->percent_off;
        }
        elseif ($promo->price) {
            $data['price'] = $promo->price;
        }

        $this->output(1, $data);
        return true;
    }

    // --------------------------------------------------------------

    /**
     * Return some JSON stuff
     *
     * @return mixed (JSON)
     */
    public function contactAction()
    {
        // Make sure recaptcha called and all
        // STAGE==LOCAL mode sets a value
        if (!$this->session->has('recaptcha')) {
            $this->output(0, 'Recaptcha is required.');
            return false;
        }

        if (!$this->session->get('recaptcha')) {
            $this->output(0, 'Recaptcha was invalid');
            return false;
        }

        $form = new \ContactForm();

        // Make sure the form is valid
        if (!$form->isValid($_POST)) {
            $errors = [];
            foreach ($form->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            $this->output(0, $errors);
            return false;
        }

        // Gather the POST stuff
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $message = $this->request->getPost('message');
        // Create the Message from a template
        $content = $this->component->email->create('contact', [
            'name' => $name,
            'email' => $email,
            'message' => $message,
        ]);

        $mail_result = $this->di->get('email', [
            [
                'to_name' => 'JREAM',
                'to_email' => 'hello@jream.com',
                'from_name' => $name,
                'from_email' => $email,
                'subject' => 'JREAM Contact Form',
                'content' => $content,
            ],
        ]);

        if (!in_array($mail_result->statusCode(), [200, 201, 202])) {
            $this->output(0, 'Error sending email');
            return false;
        }

        // Succcess
        $this->session->set('recaptcha', 0);
        $this->output(1, 'Email Sent');
        return true;
    }

    // --------------------------------------------------------------

    public function emailUnsubscribe($shaUserId, $emailId)
    {
        // Update DB or some shyt.
    }

    // --------------------------------------------------------------

    /**
     * Updates a single field.
     * @param  [type] $table [description]
     * @param  [type] $field [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function adminUpdate($model, $primary_key, $column, $value)
    {
        if (!$this->session->has('id') || $this->session->get('role') != 'admin') {
            die;
        }
    }

}

// End of File
// --------------------------------------------------------------
