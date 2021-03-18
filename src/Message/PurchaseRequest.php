<?php

namespace Omnipay\PagosWeb\Message;

class PurchaseRequest extends AbstractRequest
{
    public $docNumber;
    public $documentTypeId;
    
    public function getCustomerData()
    {
        // Datos del cliente
        $customer = [
            'FirstName' => $this->getCard()->getFirstName(),
            'LastName' => $this->getCard()->getLastName(),
            'Email' => $this->getCard()->getEmail(),
            'PhoneNumber' => $this->getCard()->getPhone(),
            'DocNumber' => $this->docNumber,
            'DocumentTypeId' => $this->documentTypeId,
            'ShippingAddress'   => [
                'Country'   => $this->getCard()->getShippingCountry(),
                'State'   => $this->getCard()->getShippingState(),
                'City'   => $this->getCard()->getShippingCity(),
                'AddressDetail'   => $this->getCard()->getShippingAddress1()
            ],
            'BillingAddress'   => [
                'Country'   => $this->getCard()->getBillingCountry(),
                'State'   => $this->getCard()->getBillingState(),
                'City'   => $this->getCard()->getBillingCity(),
                'AddressDetail'   => $this->getCard()->getBillingAddress1()
            ]
        ];

        return $customer;
    }

    public function setExtraData($parameters)
    {
        $this->docNumber = $parameters['DocNumber'];
        $this->documentTypeId = $parameters['DocumentTypeId'];
    }    
    
    public function getDataUY()
    {
        // Datos de facturacion
        $data = [
            'IsFinalConsumer'   => 'false',
            'Invoice'   =>  '',
            'TaxableAmount' =>  0
        ];

        return $data;
    }

    public function getData()
    {
        $purchaseObject = [
            'TrxToken'  =>  $this->getToken(),
            'Order'     =>  '',
            'Amount'    =>  (double)($this->formatCurrency($this->getAmount())),
            'Currency'  =>  $this->getCurrency(),
            'Customer'  =>  $this->getCustomerData(),
            'DataUY'    =>  $this->getDataUY()
        ];
        return $purchaseObject;

    }

    protected function createResponse($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }

    protected function getEndpoint()
    {
        return $this->getTestMode() ? ($this->testEndpoint . '/checkout/preferences') : ($this->liveEndpoint . '/checkout/preferences');
    }

}

?>
