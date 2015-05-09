<?php

namespace Hautzi\SystemMailBundle\SystemMailer\MailDefinition;

/**
 * This interface defines how files containing mail definitions are fetched
 */
interface ProviderInterface
{
    /**
     * Fetches the definition of a system mail as a string
     *
     * @param string $name
     * @param array  $parameters
     *
     * @return string
     */
    public function fetchMailDefinition($name, $parameters = []);
}
