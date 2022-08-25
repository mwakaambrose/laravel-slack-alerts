<?php

return [
    /*
     * The webhook URLs that we'll use to send a message to Slack.
     */
    'webhook_urls' => [
        'failed_vsla_wallet' => env('FAILED_VSLA_WALLET'),
        'failed_vsla_mobis' => env('FAILED_VSLA_MOBIS'),
    ],
];