<?php

use \Phalcon\Tag;


/**
 * @RoutePrefix("/devtools")
 */
class DevtoolsController extends \BaseController
{

    private $types = [
        'encode'   => [
            'urlencode'               => 'URL Encode',
            'urldecode'               => 'URL Decode',
            'base64_encode'           => 'Base64 Encode',
            'base64_decode'           => 'Base64 Decode',
            'json_encode'             => 'JSON Encode',
            'json_decode'             => 'JSON Decode',
            'htmlentities'            => 'HTML Entities Encode',
            'htmlspecialchars'        => 'HTML Special Chars Encode',
            'htmlspecialchars_decode' => 'HTML Special Chars Decode',
            'utf8_encode'             => 'UTF8 Encode',
            'utf8_decode'             => 'UTF8 Decode',
        ],
        'encrypt'  => [
            'md5'       => 'MD5',
            'sha1'      => 'SHA1',
            'sha256'    => 'SHA256',
            'sha512'    => 'SHA512',
            'CRC32'     => 'crc32',
            'CRC32b'    => 'crc32b',
            'SNEFRU'    => 'snefru',
            'SNEFRU256' => 'snefru256',
        ],
        'strings'  => [
            'strpos'      => 'find',
            'str_replace' => 'replace',
            'strtoupper'  => 'uppercase',
            'strtolower'  => 'lowercase',
            'ucwords'     => 'capitalize words',
        ],
        'fakedata' => [
            'names'    => 'Names',
            'numbers'  => 'Numbers',
            'whatelse' => 'What Else?',
        ],
    ];

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function onConstruct()
    {
        parent::initialize();
        Tag::setTitle('DevTools | ' . $this->di['config']['title']);
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->pick('devtools/devtools');
    }

    // --------------------------------------------------------------

    public function encodeAction()
    {
        $this->view->setVars([
            'methods' => $this->types['encode'],
        ]);

        Tag::setTitle('DevTools | Encode | ' . $this->di['config']['title']);
        $this->view->pick('devtools/encode');
    }

    // --------------------------------------------------------------

    public function doEncodeAction()
    {
        $text = $this->request->getPost('text');
        $method = $this->request->getPost('method');

        if (!array_key_exists($method, $this->types['encode'])) {
            return $this->output(0, 'Invalid method passed.');
        }

        $result = $method($text);

        return $this->output(1, $result);
    }

    // --------------------------------------------------------------

    public function encryptAction()
    {
        $this->view->setVars([
            'methods' => $this->types['encrypt'],
        ]);

        Tag::setTitle('DevTools | Encrypt | ' . $this->di['config']['title']);
        $this->view->pick('devtools/encrypt');
    }

    // --------------------------------------------------------------

    public function doEncryptAction()
    {
        $text = $this->request->getPost('text');
        $method = $this->request->getPost('method');
        $salt = trim($this->request->getPost('salt'));

        if (!array_key_exists($method, $this->types['encrypt'])) {
            return $this->output(0, 'Invalid method passed.');
        }

        $ctx = hash_init($method);
        hash_update($ctx, $text . $salt);
        $result = [
            'hash' => hash_final($ctx),
            'salt' => (bool)$salt,
        ];

        return $this->output(1, $result);
    }

    // --------------------------------------------------------------

    public function stringsAction()
    {
        $this->view->setVars([
            'methods' => $this->types['encrypt'],
        ]);

        Tag::setTitle('DevTools - Strings | ' . $this->di['config']['title']);
        $this->view->pick('devtools/strings');
    }

    // --------------------------------------------------------------

    public function doStringsAction()
    {
        $text = $this->request->getPost('text');
        $method = $this->request->getPost('method');

        if (!array_key_exists($method, $this->types['strings'])) {
            return $this->output(0, 'Invalid method passed.');
        }

        $result = $method($text);

        return $this->output(1, $result);
    }

    // --------------------------------------------------------------

    public function fakeDataAction()
    {
        $this->view->setVars([
            'methods' => $this->types['fakedata'],
        ]);

        Tag::setTitle('DevTools | Fake Data Generator | ' . $this->di['config']['title']);
        $this->view->pick('devtools/fakedata');
    }

    // --------------------------------------------------------------

    public function doFakeDataAction()
    {
        $text = $this->request->getPost('text');
        $method = $this->request->getPost('method');

        if (!array_key_exists($method, $this->types['fakedata'])) {
            return $this->output(0, 'Invalid method passed.');
        }

        $result = $method($text);

        return $this->output(1, $result);
    }

    // --------------------------------------------------------------

    public function utf8charsAction()
    {
        $this->view->setVars([
        ]);

        Tag::setTitle('DevTools | UTF 8 Characters | ' . $this->di['config']['title']);
        $this->view->pick('devtools/utf8chars');
    }

    // --------------------------------------------------------------
}
