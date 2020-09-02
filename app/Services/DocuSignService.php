<?php

namespace App\Services;

use Exception;
use SoapClient;
use SoapFault;
use SoapVar;

class DocuSignService
{

    private $soapClient;

    public function signDocument($refId, $uuid)
    {
        try {
            $this->soapClient = new DocuSignSoap(dirname(__DIR__) . '/DocuSignService.wsdl');
        } catch (SoapFault $e) {
            new Exception('There is a problem in calling docusign-service');
        }

        $xml = '<create-request-request xmlns="https://id.docusign.com/definitions/wsdl/Document-v3" 
                                        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                    <request>
                        <document id="doc-' . $uuid . '" send-to-archive="true" xsi:type="sds-document" 
                        ref-sds-id="' . $refId . '">
                            <description>Sign document</description>
                        </document>
                    </request>
                </create-request-request>';


        $soapBody = new SoapVar($xml, XSD_ANYXML);
        try {

            $result = $this->soapClient->request($soapBody);
        } catch (\Throwable $fault) {
            new Exception("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})");
        }

        return $result;
    }

}