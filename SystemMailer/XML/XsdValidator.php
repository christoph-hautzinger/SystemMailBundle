<?php

namespace Hautzi\SystemMailBundle\SystemMailer\XML;

class XsdValidator
{
    /**
     * @param string $xmlString
     * @param string $xsdFile
     *
     * @return bool
     *
     * @throws XsdValidationException when validation fails
     */
    public function validate($xmlString, $xsdFile)
    {
        libxml_use_internal_errors(true);

        $xml = new \DOMDocument();
        $xml->loadXML($xmlString);

        if (!$xml->schemaValidate($xsdFile)) {
            $messages = [];
            foreach (libxml_get_errors() as $error) {
                array_push($messages, sprintf('%s in line %d', $error->message, $error->line));
            }
            libxml_clear_errors();

            throw new XsdValidationException(implode(PHP_EOL, $messages));
        }

        return true;
    }
}
