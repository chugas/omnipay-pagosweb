<?php

namespace Omnipay\PagosWeb\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\PagosWeb\Gateway;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const API_VERSION = 'v1';

    protected $liveEndpoint = 'https://api.siemprepago.com/';
    protected $testEndpoint = 'https://testapi.siemprepago.com/';

    public function sendData($data)
    {
        if ($this->getTestMode())
            $this->validate('test_access_token');
        else
            $this->validate('public_account_key', 'private_account_key');

//        $token = $this->getTestMode() ? $this->getTestAccessToken() : $this->getAccessToken();

        $url = $this->getEndpoint() .static::API_VERSION.'/api/purchase';

        //Llamo a pedir la preferencia de pagosweb
        $httpRequest = $this->httpClient->createRequest(
            'POST',
            $url,
            array(
                'Authorization' => 'Basic '.$this->getPrivateAccountKey(),
                'Content-type' => 'application/json',
            ),
            $this->toJSON($data)
        );

        // Obtengo la preferencia
        $httpResponse = $httpRequest->send();
        return $this->createResponse((object) $httpResponse->json());
    }

    public function setAccessToken($value)
    {
        return $this->setParameter('access_token', $value);
    }

    public function getAccessToken()
    {
        //Si esta en la configuracion del plugin el access_token, lo traigo
        $access_token = $this->getParameter('access_token');
        if ($access_token)
            return $access_token;

        //Si no esta el parametro pues lo solicito a mercadopago
        $token_response = (new Gateway())->requestToken($this->getParameters());
        $params = $token_response->getData();
        return $token_response->sendData($params)->getData()->access_token;
    }

    public function setTestAccessToken($value)
    {
        return $this->setParameter('test_access_token', $value);
    }

    public function getTestAccessToken()
    {
        return $this->getParameter('test_access_token');
    }

    public function getPublicAccountKey()
    {
        return $this->getParameter('public_account_key');
    }

    public function setPublicAccountKey($value)
    {
        return $this->setParameter('public_account_key', $value);
    }

    public function getPrivateAccountKey()
    {
        return $this->getParameter('private_account_key');
    }

    public function setPrivateAccountKey($value)
    {
        return $this->setParameter('private_account_key', $value);
    }

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    public function toJSON($data, $options = 0)
    {
        if (version_compare(phpversion(), '5.4.0', '>=') === true) {
            return json_encode($data, $options | 64);
        }
        return str_replace('\\/', '/', json_encode($data, $options));
    }

    public function validate()
    {
        foreach (func_get_args() as $key) {
            $value = $this->parameters->get($key);
            if (!isset($value) || $value === '') {
                throw new InvalidRequestException("The $key parameter is required");
            }
        }
    }
}
