<?php

namespace Gentor\Mautic\Api;

use Mautic;

/**
 * Class Contacts
 * @package Gentor\Mautic\Api
 */
class Contacts extends \Mautic\Api\Contacts
{
    /**
     * @param array $data
     * @return null|object
     */
    public function createWithCompanies(array $data)
    {
        $companies = [];
        if (isset($data['companies'])) {
            $companies = $data['companies'];
            unset($data['companies']);
        }

        $response = $this->create($data);
        if (!isset($response['contact'])) {
            return null;
        }

        $user = (object)$response['contact'];
        foreach ($companies as $company) {
            $response = Mautic::companies()->create($company);
            if (isset($response['company'])) {
                $userCompany = (object)$response['company'];
                Mautic::companies()->addContact($userCompany->id, $user->id);
            }
        }

        return $user;
    }
}