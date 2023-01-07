# A simple package to help integrate FriendlyCaptcha.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ossycodes/friendlycaptcha.svg?style=flat-square)](https://packagist.org/packages/ossycodes/friendlycaptcha)
[![Total Downloads](https://img.shields.io/packagist/dt/ossycodes/friendlycaptcha.svg?style=flat-square)](https://packagist.org/packages/ossycodes/friendlycaptcha)
![GitHub Actions](https://github.com/ossycodes/friendlycaptcha/actions/workflows/main.yml/badge.svg)

This package helps in setting up and validating FriendlyCaptcha widget and response in your Laravel applications

## Installation

You can install the package via composer:

```bash
composer require ossycodes/friendlycaptcha
```

### Configuration

Add `FRIENDLY_CAPTCHA_SECRET`, `FRIENDLY_CAPTCHA_SITEKEY` and optional `FRIENDLY_CAPTCHA_PUZZLE_ENDPOINT`, `FRIENDLY_CAPTCHA_VERIFY_ENDPOINT` in **.env** file :

```
FRIENDLY_CAPTCHA_SECRET=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
FRIENDLY_CAPTCHA_SITEKEY=XXXXXXXXXXXXXXXX
FRIENDLY_CAPTCHA_PUZZLE_ENDPOINT=https://api.friendlycaptcha.com/api/v1/puzzle #optional
FRIENDLY_CAPTCHA_VERIFY_ENDPOINT=https://api.friendlycaptcha.com/api/v1/siteverify #optional
```

You can obtain your site-key from [here](https://docs.friendlycaptcha.com/#/installation?id=_1-generating-a-sitekey) and secret from [here](https://apiserver-prod.friendlycaptcha.eu/dashboard/accounts/1118678876/apikeys)

## Usage

For FriendlyCaptcha widget scripts from a CDN, add the Blade directive `@friendlyCaptchaRenderWidgetScripts` in your layout file. This should be added to the `<head>` of your document.

```blade
<html>
    <head>
        @friendlyCaptchaRenderWidgetScripts()
    </head>
    <body>
        {{ $slot }}
    </body>
</html>
```

or if you don't want to use the Blade directive you can do this instead

```php
 {!! FriendlyCaptcha::renderWidgetScripts() !!}
```

You have two options on how to add the script tag either from unpkg (default) or from jsdelivr

`@friendlyCaptchaRenderWidgetScripts()`
or
`@friendlyCaptchaRenderWidgetScripts('jsdelivr')`

`{!! FriendlyCaptcha::renderWidgetScripts() !!}`
or
`{!! FriendlyCaptcha::renderWidgetScripts('jsdelivr') !!}`

You can also host the FriendlyCaptcha widget scripts yourself:

```
npm install --save friendly-challenge@0.9.9
```

And import it in your app:

```js
import "friendly-challenge/widget";
```


Once that's done, you can call the `renderWidget()` method  in `<form>` to output the appropriate markup (friendlycaptcha widget) with your site key configured.

```blade
<form action="/" method="POST">

    {!! FriendlyCaptcha::renderWidget() !!}

    or with custom theme

    {!! FriendlyCaptcha::renderWidget(['dark-theme' => true]) !!}

    or with custom language

    {!! FriendlyCaptcha::renderWidget(['data-lang' => 'en']) !!}

    <button>
        Submit
    </button>
</form>
```

Finally On the server, use the provided validation rule to validate the CAPTCHA response.

```php
use Illuminate\Validation\Rule;

public function submit(Request $request)
{
    $request->validate([
        'frc-captcha-solution' => ['required', Rule::friendlycaptcha()],
    ]);
}
```

If you prefer to not use a macro, you can resolve an instance of the rule from the container via dependency injection or the `app()` helper.

```php
use Ossycodes\FriendlyCaptcha\Rules\FriendlyCaptcha;

public function submit(Request $request, FriendlyCaptcha $friendlyCaptcha)
{
    $request->validate([
        'frc-captcha-solution' => ['required', $friendlyCaptcha],
    ]);
}
```

```php
use Ossycodes\FriendlyCaptcha\Rules\FriendlyCaptcha;

public function submit(Request $request)
{
    $request->validate([
        'frc-captcha-solution' => ['required', app(FriendlyCaptcha::class)],
    ]);
}
```

### Testing

```bash
composer test
```

### Security

If you discover any security related issues, please email osaigbovoemmanuel1@gmail.com instead of using the issue tracker.

## Credits

-   [Osaigbovo Emmanuel](https://github.com/ossycodes)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## How do I say Thank you?

Please buy me a cup of coffee https://www.paypal.com/paypalme/osaigbovoemmanuel , Leave a star and follow me on [Twitter](https://twitter.com/ossycodes) .
