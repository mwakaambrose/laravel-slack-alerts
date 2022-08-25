<?php

namespace MwakaAmbrose\SlackAlert\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static self to(string $text)
 * @method static void message(string $text)
 *
 * @see \MwakaAmbrose\SlackAlert\SlackAlert
 */
class SlackAlert extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-slack-alert';
    }
}
