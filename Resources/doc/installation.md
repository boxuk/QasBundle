Installing the Bundle
=====================

Prerequesites
-------------

* Symfony2 - version 2.1 or higher

Installation
------------

### Step 1: Download the bundle using Composer
Installation is handled through [Composer](http://getcomposer.org). In your project's `composer.json` file, add the following:

```js
{
    "require": {
        "boxuk/qas-bundle": "~0.1"
    }
}
```

With your composer file updated, run

``` bash
$ php composer.phar update boxuk/qas-bundle
```

to download the bundle and its dependencies.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new BoxUK\QasBundle\BoxUKQasBundle(),
    );
}
```

### Step 3: Add routing information

Add routing configuration for the bundle to your application's routing file:

In YAML:

``` yaml
# app/config/routing.yml
box_uk_qas:
    resource: "@BoxUKQasBundle/Resources/config/routing.yml"
    prefix:   /
```

### Step 4: Configure the bundle

In order to query the ProWeb API the bundle needs to know where the WSDL for the service lives.

``` yaml
# app/config/config.yml
box_uk_qas:
    proweb:
        wsdl_url: http://your-server.com:2021/proweb.wsdl
```
