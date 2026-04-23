<?php

namespace KriticalIT\OpenGoogleBot;

use Craft;
use craft\base\Plugin as BasePlugin;
use craft\web\twig\variables\CraftVariable;
use KriticalIT\OpenGoogleBot\variables\OpenGoogleBotVariable;
use modules\consejeros\services\GoogleBotValidatorService;
use yii\base\Event;

/**
 * Trending Entries plugin
 *
 * @method static Plugin getInstance()
 *
 * @property GoogleBotValidatorService validator
 *
 * @author Gonzalo García Arce <info@gongarce.io>
 * @copyright Kritical IT
 * @license MIT
 */
class Plugin extends BasePlugin
{
    public string $schemaVersion = '1.0.0';

    public static function config(): array
    {
        return [
            'components' => [
                // Define component configs here...
            ],
        ];
    }

    public function init(): void
    {
        parent::init();

        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            $this->controllerNamespace = 'KriticalIT\OpenGoogleBot\console\controllers';
        }

        $this->setComponents([
            'validator' => GoogleBotValidatorService::class,
        ]);

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('openGoogleBot', OpenGoogleBotVariable::class);
            }
        );
    }
}
