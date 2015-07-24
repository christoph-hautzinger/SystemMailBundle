<?php

namespace Hautzi\SystemMailBundle\Tests\SystemMailer\XML;

use Hautzi\SystemMailBundle\SystemMailer\XML\XsdValidator;

class XsdValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $xsd = <<<EOL
<?xml version="1.0"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema"
            targetNamespace="http://christoph-hautzinger.de/schema/system-mail-bundle"
            xmlns="http://christoph-hautzinger.de/schema/system-mail-bundle"
            elementFormDefault="qualified">

<xs:element name="email">
  <xs:complexType>
    <xs:sequence>
      <xs:element name="to" type="xs:string"/>
      <xs:element name="from" type="xs:string"/>
    </xs:sequence>
  </xs:complexType>
</xs:element>

</xs:schema>
EOL;

    protected $validXml = <<<EOL
<?xml version="1.0"?>
<email xmlns="http://christoph-hautzinger.de/schema/system-mail-bundle"
       xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
       xsi:schemaLocation="http://christoph-hautzinger.de/schema/system-mail-bundle http://christoph-hautzinger.de/schema/system-mail-bundle/email-1.0.xsd">
  <to>hautzi</to>
  <from>not-hautzi</from>
</email>
EOL;

    protected $invalidXml = <<<EOL
<?xml version="1.0"?>
<email xmlns="http://christoph-hautzinger.de/schema/system-mail-bundle"
       xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
       xsi:schemaLocation="http://christoph-hautzinger.de/schema/system-mail-bundle http://christoph-hautzinger.de/schema/system-mail-bundle/email-1.0.xsd">
  <message>hautzi</message>
  <from>not-hautzi</from>
</email>
EOL;

    protected $tmpXsdFile;

    protected function setUp()
    {
        $this->tmpXsdFile = tempnam('/tmp', 'FOO');
        file_put_contents($this->tmpXsdFile, $this->xsd);
    }

    protected function tearDown()
    {
        unlink($this->tmpXsdFile);
    }


    public function testValidationSuccess()
    {
        $xsd = new XsdValidator();

        $this->assertTrue($xsd->validate($this->validXml, $this->tmpXsdFile));
    }

    /**
     * @expectedException \Hautzi\SystemMailBundle\SystemMailer\XML\XsdValidationException
     */
    public function testValidationFailsThrowsException()
    {
        $xsd = new XsdValidator();

        $xsd->validate($this->invalidXml, $this->tmpXsdFile);
    }
}
