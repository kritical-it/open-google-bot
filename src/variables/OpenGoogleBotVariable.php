<?php

namespace KriticalIT\OpenGoogleBot\variables;

use KriticalIT\OpenGoogleBot\Plugin;

class OpenGoogleBotVariable
{
    public function isGoogleBot(): bool
    {
        return Plugin::getInstance()->validator->isGoogleBot();
    }
}
