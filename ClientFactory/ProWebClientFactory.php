<?php

namespace BoxUK\QasBundle\ClientFactory;

use Zend\Soap\Client as SoapClient;

class ProWebClientFactory
{
    /**
     * @var string
     */
    private $wsdlUrl;

    /**
     * @param string $wsdlUrl URL to the WSDL for the ProWeb SOAP service
     */
    public function __construct($wsdlUrl)
    {
        $this->wsdlUrl = $wsdlUrl;
    }

    /**
     * @return SoapClient
     */
    public function createClient()
    {
        $options = array(
            'soap_version' => SOAP_1_1
        );

        $client = new SoapClient($this->wsdl, $options);

        return $client;
    }
}
