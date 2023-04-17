KikwikDbTransBundle
===================

Translation loader from database for symfony 5

Installation
------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require kikwik/db-trans-bundle
```

Configuration
-------------

Create the `config/packages/kikwik_db_trans.yaml` config file and define the locales (and clear the cache)

```yaml
kikwik_db_trans:
    domain: db_messages
    locales: [ it, en ]
```

