<?php

namespace Gentor\Mautic;


use Gentor\Mautic\Api\Contacts;
use Gentor\Mautic\Api\Form;
use Mautic\Api\Companies;
use Mautic\Auth\ApiAuth;
use Mautic\Auth\AuthInterface;
use Mautic\Exception\ContextNotFoundException;

/**
 * Class MauticService
 *
 * @package Gentor\Mautic
 */
class MauticService
{
    /**
     * @var ApiAuth|AuthInterface
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
     * @return \Gentor\Mautic\Api\Contacts
     */
    public function contacts()
    {
        return new Contacts($this->auth, $this->baseUrl);
    }

    /**
     * @return \Mautic\Api\Companies
     */
    public function companies()
    {
        return new Companies($this->auth, $this->baseUrl);
    }

    /**
     * @param $formId
     * @return \Gentor\Mautic\Api\Form
     */
    public function form($formId)
    {
        return new Form($formId, $this->baseUrl);
    }
}