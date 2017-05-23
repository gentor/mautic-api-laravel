<?php
/**
 * Created by PhpStorm.
 * User: evgen
 * Date: 23.5.2017 г.
 * Time: 2:18
 */

namespace Gentor\Mautic\Api;


use Mautic\Api\Api;
use Mautic\Auth\ApiAuth;
use Mautic\Api\Contacts as ApiContacts;

class Contacts extends ApiContacts
{
    protected $baseUrl;

    protected $api;

    public function __construct(ApiAuth $auth, $baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->api = (new \Mautic\MauticApi())->newApi('contacts', $auth, $baseUrl);
    }

    public function __call($method, array $args)
    {
        if (!method_exists($this->api, $method)) {
            throw new IntercomException("Mautic method {$method} not found in api context contacts");
        }

        return call_user_func_array([$this->api, $method], $args);
    }
}