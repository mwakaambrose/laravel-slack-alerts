<?php

namespace MwakaAmbrose\SlackAlert\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Throwable;

class SendExceptionToSlackChannelJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     */
    public int $maxExceptions = 3;

    public function __construct(private Throwable $throwable) {}

    public function handle(): void
    {
        $payload = ['type' => $this->type, 'text' => $this->throwable->getMessage()];

        Http::post($this->webhookUrl, $payload);
    }
}
