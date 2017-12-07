# Line Messaging For PHP

## About Line Messaging

This package provides a trait that adds simple events of line behavior. The package have 2 part. First, the package can help you handle those webhooks events sent by Line Platform. The second is simple medthod to communicate with Line API.

## Installation

This package can be installed through Composer.

```
$ composer require syllistudio/line-messaging
```

The service provider will automatically register itself. After that, You need to add our `Syllistudio\LineMessaging\LineMessagingProvider::class` to the array of Service Providers in file `config/app.php` You must publish the config file with:

```
php artisan vendor:publish --provider="Yamakadi\LineWebhooks\LineWebhooksServiceProvider" --tag="config"
```

This is the contents of the config file that will be published at `config/line-messaging.php`:

```php
return [
	/*
     * You need to define your channel secret and access token in your environment variables
     */
    'channel_secret' => env('LINEBOT_CHANNEL_SECRET'),
    'channel_access_token' => env('LINEBOT_CHANNEL_ACCESS_TOKEN'),
];

```

You can customize your `channel_secret` and `channel_access_token` here or define it in your environment variables.

Note: Find both `channel_secret` and `channel_access_token` in your console LINE channel.

## Usage

Mechanism to work with LINE bot is communication between the server of your bot application and the LINE Platform. When a user sends your bot a message, a webhook is triggered and the LINE Platform sends a request to your webhook URL. Your server then sends a request to the LINE Platform to respond to the user. Requests are sent over HTTPS in JSON format. 

### Create Webhook URL

You can create it easy in your routing. In the routes file of your app you must pass that route to `Route::post('/webhook', 'LineBotController@handleWebhook')` After you need to set your webhook URL in your LINE console. Then use our trait `LineWebhookReceiver` into your controller.

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Syllistudio\LineMessaging\LineWebhookReceiver;

class LineBotController extends Controller
{
	use LineWebhookReceiver;

	public function onMessageEvent($replyToken, $source, $message, $timestamp) {
		// do your work here
	}

	public function onFollowEvent($replyToken, $source, $timestamp) {
		// do your work here
	}

	public function onUnfollowEvent($source, $timestamp) {
		// do your work here
	}

	public function onJoinEvent($replyToken, $source, $timestamp) {
		// do your work here
	}

	public function onLeaveEvent($source, $timestamp) {
		// do your work here
	}
```

There are method to handle events from webhook. You can find the [full list of events types](https://developers.line.me/en/docs/messaging-api/reference/#webhook-event-objects) in the Line documentation.

Remember this register a `POST` route to a controller provided by this package. Because Line has no way of getting a csrf-token, you must add that route to the except array of the VerifyCsrfToken.

### Send Reply Message

You can send messages back to users, groups, and rooms with a `replyToken` you can get it from webhook. Just add `LineMessaging` trait in to your controller. The trait will adds method `replyMessage($replyToken, MessageBuilder $messages)` into your controller.

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Syllistudio\LineMessaging\LineWebhookReceiver;
use Syllistudio\LineMessaging\LineMessaging;
use Syllistudio\LineMessaging\MessageBuilder\TextMessageBuilder;

class LineBotController extends Controller
{
	use LineWebhookReceiver, LineMessaging;

	public function onJoinEvent($replyToken, $source, $timestamp) {
		$messages = new TextMessageBuilder("You're welcome");
		$response = $this->replyMessage($replyToken, $messages);
	}
```

Because the `replyToken` becomes invalid after a certain period of time, responses should be sent as soon as a message is received. Reply tokens can only be used once.

### Send Push Message

You can send messages to a `user`, `group`, or `room` at any time by method `pushMessage($to, MessageBuilder $messages)`.

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Syllistudio\LineMessaging\LineMessaging;
use Syllistudio\LineMessaging\MessageBuilder\TextMessageBuilder;

class ChatBot extends Model
{
	use LineMessaging;

	public sendMessage() {
		$messages = new TextMessageBuilder("You're so sexy Lady");
		$response = $this->pushMessage($userId, $messages);
	}
```

Use a `userId`, `groupId`, or `roomId` value returned in a webhook event object.

Note: Do not use the `LINE ID` found on the LINE app.

## License

The LineMessaging is open-sourced software licensed.
