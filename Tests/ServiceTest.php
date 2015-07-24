<?php

namespace Hautzi\SystemMailBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * This tests only purpose is to check if the service container configuration works properly
     */
    public function testSend()
    {
        $kernel = new \AppKernel('test', true);
        $kernel->boot();

        $container = $kernel->getContainer();

        $request = $this->prophesize(Request::class);
        $request->getLocale()->willReturn('de');

        $requestStack = $this->prophesize(RequestStack::class);
        $requestStack->getMasterRequest()->willReturn($request->reveal());
        $container->set('request_stack', $requestStack->reveal());

        try {
            $container->get('system_mailer')->send('HautziSystemMail:test');
            $this->fail('fetching a mail that does nox exist should throw an exception.');
        }
        catch (\InvalidArgumentException $e) {
            $this->assertContains('@HautziSystemMailBundle/Resources/emails/test.xml.twig', $e->getMessage());
        }
    }
}