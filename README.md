# Line Messaging For PHP

## About Line Messaging

This package provides easy integration with LINE Message API interfaces. The package contains 2 essential parts. First, a webhook that handles events sent from LINE Messaging API. Second, simple methods to communicate using Line Messaging API.

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

Mechanism to work with LINE bot is a communication between the server of your bot application and the LINE Platform. When a user sends your bot a message, LINE Messaging API will send a request to your webhook URL. The webhook created with this package will provide methods to handle those events sent from LINE Messaging API. To response to those events or communicate with your contacts, this package provide methods that let the server sends a request to the LINE Platform to respond to the user. Requests are sent over HTTPS in JSON format.

### Create Webhook URL

You can create it easily in your routing. You will first need to create Controller and use our trait `LineWekhookReceiver`. After that, in the route file of your app, you must create a route to the created Controller `Route::post('/webhook', 'YourControllerName@handleWebhook')`. This will give you the webhook URL and you will need to set this webhook URL in your LINE console to integrate your app with LINE messaging API.

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Syllistudio\LineMessaging\LineWebhookReceiver;

class YourControllerName extends Controller
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

These are pre-defined methods that handle events received from our webhook. You can find the full list of events types and its detail information here in the [Line Messaging API documentation](https://developers.line.me/en/docs/messaging-api/reference/#webhook-event-objects).

**NOTE**: Line event responses will not have csrf-token included thus you must add your route to the except array of the VerifyCsrfToken middleware.

### Sending Message

This package provides `LineMessaging` trait that support below messaging methods.

- Send reply message
- Send push message

#### Send Reply Message

You can send messages back to users, groups, and rooms from a `replyToken` received from webhook events. Just add `LineMessaging` trait in to your controller. The trait will adds method `replyMessage($replyToken, MessageBuilder $messages)` into your controller.

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

#### Send Push Message

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

Use a `userId`, `groupId`, or `roomId` value returned from webhook event object.

**NOTE**: Do not use the `LINE ID` found on the LINE app.

## License

The LineMessaging is open-sourced software licensed.
