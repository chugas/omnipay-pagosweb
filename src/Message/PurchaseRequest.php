<?php

namespace Omnipay\PagosWeb\Message;

class PurchaseRequest extends AbstractRequest
{
    public function getCustomerData()
    {
        $extraData = $this->getExtraData();
        $docNumber = isset($extraData['DocNumber']) ? $extraData['DocNumber'] : null;
        $documentTypeId = isset($extraData['DocumentTypeId']) ? $extraData['DocumentTypeId'] : null;

        // Datos del cliente
        $customer = [
            'FirstName' => $this->getCard()->getFirstName(),
            'LastName' => $this->getCard()->getLastName(),
            'Email' => $this->getCard()->getEmail(),
            'PhoneNumber' => $this->getCard()->getPhone(),
            'DocNumber' => $docNumber,
            'DocumentTypeId' => $documentTypeId,
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
        $extraData = $this->getExtraData();
        $order = isset($extraData['OrderId']) ? (string)$extraData['OrderId'] : '';
        $installments = isset($extraData['Installments']) ? (int)$extraData['Installments'] : 1;

        $targetCountryISO = isset($extraData['TargetCountryISO']) ? $extraData['TargetCountryISO'] : 'UY';

        $purchaseObject = [
            'TrxToken'          => $this->getToken(),
            'Order'             => $order,
            'Installments'      => $installments,
            'Capture'           => true,
            'TargetCountryISO'  => $targetCountryISO,
            'Amount'            => (double)($this->formatCurrency($this->getAmount())),
            'Currency'          => $this->getCurrency(),
            'Customer'          => $this->getCustomerData(),
            'DataUY'            => $this->getDataUY()
        ];
        return $purchaseObject;

    }

    protected function createResponse($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
