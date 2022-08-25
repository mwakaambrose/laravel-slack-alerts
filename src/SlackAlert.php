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
            throw new Exception("A slack webhook URL is not yet configured");
        }

        $jobArguments = [
            'text' => $text,
            'type' => 'mrkdown'
        ];

        dispatch(Config::getJob($jobArguments, $webhookUrl));
    }

    public function exception(Throwable $throwable): void
    {
        // $trace = "";
        // for($i = 0; $i < 5; $i++){
        //     $traces =  $throwable->getTrace()[$i];
        //     if(is_array($traces)){
        //         foreach ($traces as $trace_line) {
        //             if(!is_array($trace_line)){
        //                 $trace .= "{$trace_line} \n";
        //             }
        //         }
        //     }
        // }

        $msg = new Message(
            ephemeral: true,
            blocks: [
                new Header("[" . strtoupper(config("app.env")) . "] ".$throwable->getMessage()),
                new Divider(),
                new Section("On {$throwable->getFile()}"),
                new Section("At line {$throwable->getLine()}"),
                new Divider(),
                // Context::fromText($trace),
                Context::fromText("Your Slack Buddy ❤️"),
            ]
        );
        
        $msg->validate();
        $message = $msg->toArray() + [
            "channel" => $this->webhookUrlName,
        ];

        dispatch(Config::getJob($message));
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
        dispatch(Config::getJob($message));
    }
}