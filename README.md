# Line Messaging For PHP

## About Line Messaging

This package provides a trait that adds simple events of line behavior.

The package have 2 part. First, the package can help you handle those webhooks events sent by Line Platform.

The second is simple medthod to communicate with Line API.

## Installation

This package can be installed through Composer.

```
$ composer require syllistudio/line-messaging
```

The service provider will automatically register itself.

After that, You need to add our `Syllistudio\LineMessaging\LineMessagingProvider::class` to the array 

of Service Providers in file `config/app.php`

You must publish the config file with:
```
php artisan vendor:publish --provider="Yamakadi\LineWebhooks\LineWebhooksServiceProvider" --tag="config"
```

This is the contents of the config file that will be published at `config/line-messaging.php`:

```
return [
	/*
     * You need to define your channel secret and access token in your environment variables
     */
    'channel_secret' => env('LINEBOT_CHANNEL_SECRET'),
    'channel_access_token' => env('LINEBOT_CHANNEL_ACCESS_TOKEN'),
];

```

You can customize your `channel_secret` and `channel_access_token` here or define it in your environment variables.

Ps. Find both `channel_secret` and `channel_access_token` in your console LINE channel.

## Usage

## Contributing

Thank you for considering contributing to the ?! The contribution guide can be found in the [documentation](http://).

## License

The .. is open-sourced software licensed under the [?](http://).
