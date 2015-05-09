<?php

namespace Hautzi\SystemMailBundle\SystemMailer\MailDefinition;

use Hautzi\SystemMailBundle\SystemMailer\ParsedMessage;

/**
 * This interface defines how mail definitions will be parsed
 *
 * So we put in some string representation of a mail definition (which can be some pice of xml or yml or whatelse) and
 * the implementation of this interface will parse this to an instance of "ParsedMessage" for further processing.
 */
interface ParserInterface
{
    /**
     * @param string $mailDefinition
     * @param string $locale
     *
     * @return ParsedMessage
     */
    public function parseMailDefinition($mailDefinition, $locale = null);
}
