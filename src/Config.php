<?php

namespace MwakaAmbrose\SlackAlert;

use Exception;
use MwakaAmbrose\SlackAlert\Exceptions\JobClassDoesNotExist;
use MwakaAmbrose\SlackAlert\Exceptions\WebhookUrlNotValid;
use MwakaAmbrose\SlackAlert\Jobs\SendToSlackChannelJob;

class Config
{
    public static function getJob(array $arguments): SendToSlackChannelJob
    {
        if(!$arguments['channel']){
            throw new Exception("No channel name provided");
        }

        $webhookUrl = Config::getWebhookUrl($arguments['channel']);

        if (! $webhookUrl) {
            throw new Exception("A slack webhook URL is not yet configured");
        }

        return new SendToSlackChannelJob($arguments, $webhookUrl);
    }

    public static function getWebhookUrl(string $name): string|null
    {
        if (filter_var($name, FILTER_VALIDATE_URL)) {
            return $name;
        }

        $url = config("slack-alert.webhook_urls.{$name}");

        if (is_null($url)) {
            return null;
        }

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw WebhookUrlNotValid::make($name, $url);
        }

        return $url;
    }
}