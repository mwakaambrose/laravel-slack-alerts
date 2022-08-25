# Quickly send a message to Slack | Entendable for building slack bots/apps

This package can quickly send alerts to Slack. You can use this to notify yourself of any noteworthy events happening in your app.

```php
use MwakaAmbrose\SlackAlert\Facades\SlackAlert;

SlackAlert::string("You have a new subscriber to the {$newsletter->name} newsletter!");
```

Under the hood, a job is used to communicate with Slack. This prevents your app from failing in case Slack is down.

## Installation

You can install the package via composer:

```bash
composer require mwakaambrose/laravel-slack-alert
```

You can set a `SLACK_ALERT_WEBHOOK` env variable containing a valid Slack webhook URL. You can learn how to get a webhook URL [in the Slack API docs](https://api.slack.com/messaging/webhooks).


Alternatively, you can publish the config file with:

```bash
php artisan vendor:publish --tag="slack-alert-config"
```

This is the contents of the published config file:

```php
return [
    /*
     * The webhook URLs that we'll use to send a message to Slack.
     */
    'webhook_urls' => [
        'default' => env('SLACK_ALERT_WEBHOOK'),
    ],
];

```

## Usage

To send a message to Slack, simply call `SlackAlert::string()` and pass it any message you want.

```php
SlackAlert::string("You have a new subscriber to the {$newsletter->name} newsletter!");
```

## Using multiple webhooks

You can also use an alternative webhook, by specify extra ones in the config file.

```php
// in config/slack-alert.php

'webhook_urls' => [
    'default' => 'https://hooks.slack.com/services/XXXXXX',
    'marketing' => 'https://hooks.slack.com/services/YYYYYY',
],
```

The webhook to be used can be chosen using the `to` function.

```php
use MwakaAmbrose\SlackAlert\Facades\SlackAlert;

SlackAlert::to('marketing')->string("You have a new subscriber to the {$newsletter->name} newsletter!");
```

### Using a custom webhooks

The `to` function also supports custom webhook urls.

```php
use MwakaAmbrose\SlackAlert\Facades\SlackAlert;

SlackAlert::to('https://custom-url.com')->message("You have a new subscriber to the {$newsletter->name} newsletter!");
```

## Formatting

### Markdown
You can format your messages with Slack's markup. Learn how [in the Slack API docs](https://slack.com/help/articles/202288908-Format-your-messages).

```php
use MwakaAmbrose\SlackAlert\Facades\SlackAlert;

SlackAlert::string("A message *with some bold statements* and _some italicized text_.");
```

Links are formatted differently in Slack than the classic markdown structure.

```php
SlackAlert::string("<https://theonehq.com|This is a link to our homepage>");
```

### Emoji's

You can use the same emoji codes as in Slack. This means custom emoji's are also supported.
```php
use MwakaAmbrose\SlackAlert\Facades\SlackAlert;

SlackAlert::string(":smile: :custom-code:");

```

### Mentioning

You can use mentions to notify users and groups. Learn how [in the Slack API docs](https://api.slack.com/reference/surfaces/formatting#mentioning-users).
```php
use MwakaAmbrose\SlackAlert\Facades\SlackAlert;

SlackAlert::string("A message that notifies <@username> and everyone else who is <!here>")

```
### Sending an exception

Yo can send an exception to slack by calling the exception function and passing in any instance of throwable. You will receive the message, file, line number and apps environment on slack.
```php
SlackAlert::to("failed_vsla_wallet")->exception(new \Exception("This is a test exception"));
```
### More advanced

You can pass a callback function the returns an instance of `SlackPhp\Blockkit\Surfaces\Message` to construct your own robust message blocks in slack. See [the packaages home](https://github.com/slack-php/slack-php-block-kit) for more information.

```php

SlackAlert::to("failed_vsla_wallet")->send(function(){
    return new Message(
        ephemeral: true,
        blocks: [
            new Section('Don\'t you just love XKCD?'),
            new Divider(),
            new BlockImage(
                title: 'Team Chat',
                imageUrl: 'https://imgs.xkcd.com/comics/team_chat.png',
                altText: 'Comic about the stubbornness of some people switching chat clients',
            ),
        ]);
    });
```

## Testing

```bash
composer test
```

## Credits
- [slack-php/slack-php-block-kit](https://github.com/slack-php/slack-php-block-kit)
- [spatie/laravel-slack-alert](https://github.com/spatie/laravel-slack-alert)
- [Niels Vanpachtenbeke](https://github.com/Nielsvanpach)
- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
