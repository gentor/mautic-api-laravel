<?php

namespace Gentor\Mautic\Api;


/**
 * Class Form
 * @package Gentor\Mautic\Api
 */
class Form
{
    /**
     * Form ID
     *
     * @var int
     */
    protected $id;

    /**
     * Mautic base url
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * @var Cookie
     */
    protected $cookie;

    /**
     * Form constructor.
     * @param $formId
     * @param $baseUrl
     */
    public function __construct($formId, $baseUrl)
    {
        $this->id = $formId;
        $this->baseUrl = $baseUrl;
        $this->cookie = new Cookie();
    }

    /**
     * Submit the $data array to the Mautic form
     * Returns array containing info about the request, response and cookie
     *
     * @param  array $data
     *
     * @return array
     */
    public function submit(array $data)
    {
        $originalCookie = $this->cookie->getSuperGlobalCookie();
        $response = [];
        $request = $this->prepareRequest($data);

        $ch = curl_init($request['url']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request['query']);

        if (isset($request['header'])) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request['header']);
        }

        if (isset($request['referer'])) {
            curl_setopt($ch, CURLOPT_REFERER, $request['referer']);
        }

        if (isset($request['cookie'])) {
            curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie->createCookieFile());
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $result = curl_exec($ch);
        $response['info'] = curl_getinfo($ch);
        $response['header'] = substr($result, 0, $response['info']['header_size']);
        $response['content'] = htmlentities(substr($result, $response['info']['header_size']));
        curl_close($ch);

        if ($sessionId = $this->getSessionIdFromHeader($response['header'])) {
            $this->cookie->setSessionId($sessionId);
        }

        if ($contactId = $this->getContactIdFromHeader($response['header'], $sessionId)) {
            $this->cookie->setContactId($contactId);
        }

        return [
            'original_cookie' => $originalCookie,
            'new_cookie' => $this->cookie->toArray(),
            'request' => $request,
            'response' => $response,
        ];
    }

    /**
     * Finds the session ID hash in the response header
     *
     * @param  string $headers
     *
     * @return string|null
     */
    public function getSessionIdFromHeader($headers)
    {
        if (!$headers) {
            return null;
        }

        preg_match_all("/mautic_session_id=(.+?);/", $headers, $matches);

        if (isset($matches[1])) {
            return end($matches[1]);
        }

        return null;
    }

    /**
     * Finds the Mautic Contact ID hash in the response header
     *
     * @param  string $headers
     * @param  string $sessionId
     *
     * @return int|null
     */
    public function getContactIdFromHeader($headers, $sessionId)
    {
        if (!$headers || !$sessionId) {
            return null;
        }

        preg_match("/$sessionId=(.+?);/", $headers, $matches);

        if (isset($matches[1])) {
            return (int)$matches[1];
        }

        return null;
    }

    /**
     * Prepares data for CURL request based on provided form data, $_COOKIE and $_SERVER
     *
     * @param  array $data
     *
     * @return array
     */
    public function prepareRequest(array $data)
    {
        $request = ['header'];

        if (isset($data['ipAddress'])) {
            $contactIp = $data['ipAddress'];
        } else {
            $contactIp = $this->getIpFromServer();
        }

        if (!empty($contactIp)) {
            $request['header'][] = "X-Forwarded-For: $contactIp";
        }

        if ($sessionId = $this->cookie->getSessionId()) {
            $request['header'][] = "Cookie: mautic_session_id=$sessionId";
        }

        $data['formId'] = $this->id;

        // return has to be part of the form data array so Mautic would accept the submission
        if (!isset($data['return'])) {
            $data['return'] = '';
        }

        $request['url'] = $this->getUrl();
        $request['data'] = ['mauticform' => $data];

        if ($contactId = $this->cookie->getContactId()) {
            $request['data']['mtc_id'] = $contactId;
        }

        if (isset($_SERVER['HTTP_REFERER'])) {
            $request['referer'] = $_SERVER["HTTP_REFERER"];
        }

        $request['query'] = http_build_query($request['data']);

        return $request;
    }

    /**
     * Builds the form URL
     *
     * @return string
     */
    public function getUrl()
    {
        return sprintf('%s/form/submit?formId=%d', $this->baseUrl, $this->id);
    }

    /**
     * Returns the Form ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Guesses IP address from $_SERVER
     *
     * @return string
     */
    public function getIpFromServer()
    {
        $ip = '';
        $ipHolders = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR'
        ];
        foreach ($ipHolders as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    // Multiple IPs are present so use the last IP which should be
                    // the most reliable IP that last connected to the proxy
                    $ips = explode(',', $ip);
                    array_walk($ips, create_function('&$val', '$val = trim($val);'));
                    $ip = end($ips);
                }
                $ip = trim($ip);
                break;
            }
        }
        return $ip;
    }
}