<?php

namespace KriticalIT\OpenGoogleBot\variables;

use KriticalIT\OpenGoogleBot\Plugin;
use modules\consejeros\services\GoogleBotValidatorService;

class OpenGoogleBotVariable
{
    public function get(): GoogleBotValidatorService
    {
        return Plugin::getInstance()->validator;
    }
}
