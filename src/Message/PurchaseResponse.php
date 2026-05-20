<?php

namespace Omnipay\PagosWeb\Message;

use Omnipay\Common\Message\AbstractResponse;

class PurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data->Response) && !empty($this->data->Response->PurchaseId) && empty($this->data->Errors);
    }

    public function getMessage()
    {
        if (isset($this->data->Errors) && is_array($this->data->Errors) && count($this->data->Errors) > 0) {
            return $this->data->Errors[0]->Message ?? 'Error desconocido de la pasarela';
        }
        return parent::getMessage();
    }

    /**
     * Redirect for the Payment URL
     * @return boolean
     */
    public function isRedirect()
    {
        return true;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }

    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return $this->getRequest()->getTestMode() ? $this->data->sandbox_init_point : $this->data->init_point;
        }
    }

}

?>
