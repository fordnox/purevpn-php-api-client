[![Build Status](https://secure.travis-ci.org/fordnox/purevpn-php-api-client.png?branch=master)](http://travis-ci.org/fordnox/purevpn-php-api-client)

purevpn-php-api-client
======================

API wrapper for PureVPN

### Installing via Composer

The recommended way to install library is through [Composer](http://getcomposer.org).

1. Add ``fordnox/purevpn-php-api-client`` as a dependency in your project's ``composer.json`` file:

        {
            "require": {
                "fordnox/purevpn-php-api-client": "dev-master"
            }
        }

2. Download and install Composer:

        curl -s http://getcomposer.org/installer | php

3. Install your dependencies:

        php composer.phar install

4. Require Composer's autoloader

    Composer also prepares an autoload file that's capable of autoloading all of the classes in any of the libraries that it downloads. To use it, just add the following line to your code's bootstrap process:

        require 'vendor/autoload.php';

You can find out more on how to install Composer, configure autoloading, and other best-practices for defining dependencies at [getcomposer.org](http://getcomposer.org).

Example Code
======================

    $options = array(
        'api_user'      =>  'username',
        'api_password'  =>  'password',
    );
    $api = new Fordnox\Purevpn\Purevpn($options);
    $account = new Fordnox\Purevpn\Account();
    $account->setUsername('username');
    $account = $api->getAccountStatus($account);
    print $account->isEnabled();