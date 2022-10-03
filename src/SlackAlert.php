<?php

namespace MwakaAmbrose\SlackAlert;

use Exception;
use SlackPhp\BlockKit\Blocks\Context;
use Throwable;
use SlackPhp\BlockKit\Blocks\Header;
use SlackPhp\BlockKit\Blocks\Divider;
use SlackPhp\BlockKit\Blocks\Section;
use SlackPhp\BlockKit\Surfaces\Message;

class SlackAlert
{
    protected string $webhookUrlName = 'default';

    public function to(string $webhookUrlName): self
    {
        $this->webhookUrlName = $webhookUrlName;

        return $this;
    }

    public function string(string $text): void
    {
        $webhookUrl = Config::getWebhookUrl($this->webhookUrlName);

        if (! $webhookUrl) {
            return;
        }

        $jobArguments = [
            'text' => $text,
            'type' => 'mrkdown'
        ];

        $job = Config::getJob($jobArguments, $webhookUrl);
        if ($job) {
            dispatch($job);
        }
    }

    public function exception(Throwable $throwable): void
    {

        $msg = Message::new()
            ->ephemeral()
            ->header("[" . strtoupper(config("app.env")) . "] ".$throwable->getMessage())
            ->divider()
            ->text("On {$throwable->getFile()}")
            ->text("At line {$throwable->getLine()}")
            ->divider()
            ->text("Your Slack Buddy ❤️");
            
        $msg->validate();
        $message = $msg->toArray() + [
            "channel" => $this->webhookUrlName,
        ];

        $job = Config::getJob($message);
        if ($job) {
            dispatch($job);
        }
    }

    public function send(callable $function)
    {
        if(!is_callable($function)){
            throw new Exception("You need to provide a callable function");
        }

        $message = $function();
        $message->validate();
        $message = $message->toArray() + [
            "channel" => $this->webhookUrlName,
        ];
        
        $job = Config::getJob($message);
        if ($job) {
            dispatch($job);
        }
    }
}