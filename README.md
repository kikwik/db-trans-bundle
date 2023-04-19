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
    locales: [ it, en ]
```


Run the `kikwik:db-trans:import-messages` command to init the database translation and import messages from an existing catalogue, 
pass the domain as parameter (for each enabled locale will be created a file named `db_domain.locale.db` in the translation directory):

```console
$ ./bin/console kikwik:db-trans:import-messages messages
```