# Gravity Forms Installer

[![Packagist](https://img.shields.io/packagist/v/arnaud-ritti/gravityforms-installer.svg?maxAge=3600)](https://packagist.org/packages/arnaud-ritti/gravityforms-installer)[![Packagist](https://img.shields.io/packagist/l/arnaud-ritti/gravityforms-installer.svg?maxAge=2592000)](https://github.com/arnaud-ritti/gravityforms-installer/blob/master/LICENSE)![](https://github.com/arnaud-ritti/gravityforms-installer/workflows/Master%20Build/badge.svg)
[![Dependabot](https://badgen.net/badge/Dependabot/enabled/green?icon=dependabot)](https://dependabot.com/)
[![Coverage Status](https://coveralls.io/repos/github/arnaud-ritti/gravityforms-installer/badge.svg?branch=master)](https://coveralls.io/github/arnaud-ritti/gravityforms-installer?branch=master)

A composer plugin that makes installing [Gravity Forms] with [composer] easier. 

It reads your :key: Gravity Forms key from the **environment** or a **.env file**.

[Gravity Forms]: https://www.gravityforms.com/
[composer]: https://github.com/composer/composer

## Usage

> This plugin is compatible with Both Composer 2.x (latest) and 1.x

**1. Add our [Gravity Forms Composer Bridge](https://github.com/arnaud-ritti/gravityforms-composer-bridge) repository to the [`repositories`][composer-repositories] field in `composer.json`**
> This repository simply provides a periodically updated [packages.json](https://arnaud-ritti.github.io/gravityforms-composer-bridge/composer/v2/packages.json), that redirects composer to the provided downloads. 
Note that this repository **does not** provide any Gravity Forms packages itself, it only tells Composer where it can find packages.
Secondly it is important to note that **your license key is not submitted to the repository**, since the installer downloads the zip files directly from servers.

**Why this repository?**

Since it enables you to use `gravityforms/gravityforms` package with version constraints like any normal Packagist package.

```json
{
  "type": "composer",
  "url": "https://arnaud-ritti.github.io/gravityforms-composer-bridge/composer/v1/wordpress-plugin/"
}
```

This installs the package as `wordpress-plugin` type, in case you want a different type, use the following URL:

wordpress-muplugin:
> `https://arnaud-ritti.github.io/gravityforms-composer-bridge/composer/v1/wordpress-muplugin/`

wpackagist-plugin:
> `https://arnaud-ritti.github.io/gravityforms-composer-bridge/composer/v1/wpackagist-plugin/`

library:
> `https://arnaud-ritti.github.io/gravityforms-composer-bridge/composer/v1/library/`


**2. Make your key available**

There are 3 ways to make the GRAVITYFORMS_KEY available:
- Using the GRAVITYFORMS_KEY environment variable
- `.env` file
- Setting `gravityforms-key` in `$COMPOSER_HOME/config.json`

Select the one that best matches your setup:

***2.a Using the GRAVITYFORMS_KEY Environment variable***

Set the environment variable **`GRAVITYFORMS_KEY`** to your [Gravity Forms key][gravityforms-account].

***2.b Use a .env file***

Alternatively you can add an entry to your **`.env`** file:

```ini
# .env (same directory as composer.json)
GRAVITYFORMS_KEY=Your-Key-Here
```

***2.c. Setting the key in `$COMPOSER_HOME/config.json`***

You specify the `gravityforms-key` in the `config` section of your `$COMPOSER_HOME/config.json`
```json
{
  "config": {
    "gravityforms-key": "Your-Key-Here"
  }
}
```
> `$COMPOSER_HOME` is a hidden, global (per-user on the machine) directory that is shared between all projects.
> By default it points to `C:\Users\<user>\AppData\Roaming\Composer` on Windows and `/Users/\<user\>/.composer` on macOS. 
> On *nix systems that follow the XDG Base Directory Specifications, it points to `$XDG_CONFIG_HOME/composer`. 
> On other *nix systems, it points to `/home/\<user\>/.composer`.

**3. Require Gravity Forms**

```sh
composer require gravityforms/gravityforms
```

***3.b. Install add-on***

```sh
composer require gravityforms/<slug>
```

Example :
```sh
composer require gravityforms/gravityformsactivecampaign
```

[composer-repositories]: https://getcomposer.org/doc/04-schema.md#repositories
[gravityforms-account]: https://www.gravityforms.com/wp-login.php
