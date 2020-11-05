<?php

namespace Omnipay\PagosWeb;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'PagosWeb';
    }

    public function getDefaultParameters()
    {
        return array(
            'public_account_key' => '',
            'private_account_key' => '',
            'testMode' => false,
        );
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

    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }

    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
    }

    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    public function setDescription($value)
    {
        return $this->setParameter('description', $value);
    }

    public function getDescription()
    {
        return $this->getParameter('description');
    }

    public function setReturnUrl($value)
    {
        return $this->setParameter('returnUrl', $value);
    }

    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }

    public function setCancelUrl($value)
    {
        return $this->setParameter('cancelUrl', $value);
    }

    public function getCancelUrl()
    {
        return $this->getParameter('cancelUrl');
    }

    public function getGrantType()
    {
        return $this->getParameter('grant_type');
    }

    public function setGrantType($value)
    {
        return $this->setParameter('grant_type', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PagosWeb\Message\PurchaseRequest', $parameters);
    }

    public function requestToken(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PagosWeb\Message\TokenRequest', $parameters);
    }
}
