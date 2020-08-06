<?php

namespace Omnipay\PagosWeb\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\PagosWeb\Gateway;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveEndpoint = 'https://api.siemprepago.com/v1/api/purchase';
    protected $testEndpoint = 'https://testapi.siemprepago.com/v1/api/purchase';

    public function sendData($data)
    {

        $this->validate('private_account_key');

        $url = $this->getEndpoint();

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

    public function setToken($value)
    {
        return $this->setParameter('PWToken', $value);
    }

    public function getToken()
    {
        return $this->getParameter('PWToken');

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
