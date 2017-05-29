<?php

namespace Gentor\Mautic;


use Mautic\Auth\ApiAuth;
use Mautic\Exception\ContextNotFoundException;

/**
 * Class MauticService
 *
 * @package Gentor\Mautic
 */
class MauticService
{
    /**
     * @var ApiAuth
     */
    protected $auth;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * MauticService constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->auth = (new ApiAuth)->newAuth([
            'userName' => $config['userName'],
            'password' => $config['password'],
        ], 'BasicAuth');

        $this->baseUrl = $config['baseUrl'];
    }

    /**
     * @param $apiContext
     * @param $args
     * @return mixed
     * @throws ContextNotFoundException
     */
    public function __call($apiContext, $args)
    {
        $apiContext = ucfirst($apiContext);

        $class = 'Gentor\\Mautic\\Api\\' . $apiContext;
        if (!class_exists($class)) {
            $class = 'Mautic\\Api\\' . $apiContext;
        }

        if (!class_exists($class)) {
            throw new ContextNotFoundException("A context of '$apiContext' was not found.");
        }

        return new $class($this->auth, $this->baseUrl);
    }

    /**
     * @param $formId
     * @param array $data
     * @return mixed
     */
    public function formSend($formId, array $data)
    {
        $url = $this->baseUrl . '/form/submit?formId=' . $formId;
        $data['formId'] = $formId;
        $post = http_build_query(['mauticform' => $data]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}