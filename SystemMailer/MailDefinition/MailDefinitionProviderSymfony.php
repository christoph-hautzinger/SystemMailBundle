<?php

namespace Hautzi\SystemMailBundle\SystemMailer\MailDefinition;

use Symfony\Component\HttpKernel\KernelInterface;

class MailDefinitionProviderSymfony implements ProviderInterface
{
    protected $kernel;
    protected $twig;

    /**
     * @param KernelInterface   $kernel
     * @param \Twig_Environment $twig
     */
    public function __construct(KernelInterface $kernel, \Twig_Environment $twig)
    {
        $this->kernel = $kernel;
        $this->twig = $twig;
    }

    /**
     * Fetches the definition of a system mail as a string
     *
     * Here we put in something like "App:your-system-mail" and this class will look up in
     * "AppBundle/Resources/emails/your-system-email.xml.twig". Take care not to provide the "Bundle" suffix for the
     * name parameter as it is added somewhere behind this lines of this method...
     *
     * Everything will be parsed by twig which adds immense superpowers to the configuration. You can do everything
     * you can imagine.
     *
     * @param string $name
     * @param array  $parameters
     *
     * @return string
     */
    public function fetchMailDefinition($name, $parameters = [])
    {
        $resource = $this->locateResource($name);

        return $this->twig->render($resource, $parameters);
    }

    /**
     * @param $name
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function locateResource($name)
    {
        // check format Bundle:emailXmlTemplateName
        if (!preg_match('@^([^:]+):([^:]+)$@', $name, $parts)) {
            $message = sprintf('"%s" should look like YourBundle:emailXmlTemplateName', $name);
            throw new \InvalidArgumentException($message);
        }

        $locator = sprintf('@%sBundle/Resources/emails/%s.xml.twig', $parts[1], $parts[2]);
        $resource = $this->kernel->locateResource($locator);

        return $resource;
    }
}
