# SentryMagento
Magento 1.9 module for Sentry JavaScript errors tracking

# Installation
1. Create Sentry account: https://sentry.io/signup/
2. Go to documentation: https://docs.sentry.io/clients/javascript/install/
3. Copy you application URL (placed in Raven.config) ![alt text](https://raw.githubusercontent.com/macopedia/SentryMagento/master/readme/images/sentry-config.png)
4. Install module
5. Paste your application URL in Magento settings
6. Set your "Environment type" in Magento settings
7. Enable module in Magento settings
8. Clean Magento cache
9. Try to produce error in JavaScript and check your project in Sentry account :)

#Reporting PHP Exceptions / System Log errors and PHP FATAL errors via patches

You can patch the core magento files to report PHP errors.
Patches exist to trap 

* FATAL errors (index.php) 
* Exceptions (Any exception that will be written to var/log/exception.log) (app/Mage.php)
* system logs (Any messages above DEBUG level that is written to var/log/system.log)

In admin you can set ignore strings to define errors not to be pushed to sentry (example is payment failure errors)

##Why use patches?

The FATAL error trap needs to be injected as early as possible. This is done in index.php just before the mage 
environment is initialized. This will ensure that ANY FATAL error in PHP is reported. Placing this in an event 
(or rewrite etc) will be 'to late', and not setup/initialised early enough.

The patches to app/Mage.php *could* be done via extending the logger system. I simply did not see the point, as the 
patches are easier to maintain, and since I am already patching index.php, the same pattern is used to adjust more
sentry logging.

##Applying patches using composer.

I use composer to install core magento. 

It is thus possible to apply the patches via composer, and any updates to the core files will re-apply the patches.

* In composer require ```"cweagans/composer-patches": "~1.0"```
* Add a 'patches' section to the Extra section: (I use ```openmage/magento-lts``` for core magento composer install)


```json
"extra": {
    "patches": {
      "openmage/magento-lts": {
        "Patch Mage.php to send reports to sentry" : "../patches/sentry_exceptions.txt",
        "Sentry FATAL PHP ERROR catcher" : "../patches/sentry_index_php_fatal_catcher.txt"
      }
    }
  }
```

# Contribute

* Original, js on module - Macopedia
* Additional options for js, patches for php - Lucas van Staden (ProxiBlue)