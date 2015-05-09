<?php

namespace Hautzi\SystemMailBundle\Tests\SystemMailer\MailDefinition;

use Hautzi\SystemMailBundle\SystemMailer\MailDefinition\MailDefinitionProviderSymfony;
use Symfony\Component\HttpKernel\KernelInterface;

class MailDefinitionProviderSymfonyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MailDefinitionProviderSymfony
     */
    protected $fixture;

    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    protected function setUp()
    {
        $this->kernel = $this->prophesize(KernelInterface::class);
        $this->twig = $this->prophesize(\Twig_Environment::class);

        $this->fixture = new MailDefinitionProviderSymfony($this->kernel->reveal(), $this->twig->reveal());
    }

    public function testFetchDefinitionResolvesProperlyAndFetchesXml()
    {
        $this->kernel->locateResource('@AppBundle/Resources/emails/path/to/mail.xml.twig')->willReturn('/path/to/mail.xml.twig')->shouldBeCalled();
        $this->twig->render('/path/to/mail.xml.twig', ['param1' => 'wuhuuuu'])->willReturn('<email></email>')->shouldBeCalled();

        $this->assertEquals('<email></email>', $this->fixture->fetchMailDefinition('App:path/to/mail', ['param1' => 'wuhuuuu']));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testResourceFormatWithoutBundleNameThrowsException()
    {
        $this->fixture->fetchMailDefinition('path/to/mail', ['param1' => 'wuhuuuu']);
    }
}
