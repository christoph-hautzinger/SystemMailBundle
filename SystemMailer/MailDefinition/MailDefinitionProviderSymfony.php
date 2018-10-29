<?php

namespace Hautzi\SystemMailBundle\SystemMailer\MailDefinition;

class MailDefinitionProviderSymfony implements ProviderInterface
{
    protected $twig;

    /**
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Fetches the definition of a system mail as a string
     *
     * Here we put in something like "path/to/your-system-mail.xml.twig"
     *
     * Everything will be parsed by twig which adds immense superpowers to the configuration. You can do everything
     * you can imagine.
     *
     * @param       $name
     * @param array $parameters
     *
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function fetchMailDefinition($name, $parameters = [])
    {
        return $this->twig->render($name, $parameters);
    }
}
