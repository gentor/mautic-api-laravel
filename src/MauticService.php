<?php

namespace Gentor\Mautic;


use Mautic\Auth\ApiAuth;
use Mautic\Exception\ContextNotFoundException;

/**
 * Class MauticService
 *
 * @package Gentor\Mautic
 *
 * @method \Mautic\Api\Contacts contacts()
 * @method \Mautic\Api\Companies companies()
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
     * @var Contacts
     */
    public $contacts;

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

    public function __call($apiContext, $args)
    {
        $apiContext = ucfirst($apiContext);

        $class = 'Mautic\\Api\\' . $apiContext;

        if (!class_exists($class)) {
            throw new ContextNotFoundException("A context of '$apiContext' was not found.");
        }

        return new $class($this->auth, $this->baseUrl);
    }
}