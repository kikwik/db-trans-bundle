KikwikDbTransBundle
===================

Translation loader from database for symfony 5.

This bundle is inspired by the work of andrew72ru in [creative/symfony-db-i18n-bundle](https://github.com/crtweb/symfony-db-i18n-bundle)

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

Run the `kikwik:db-trans:import-messages` command to init the database translation and import messages from an existing catalogue with the domain as parameter:

```console
$ ./bin/console kikwik:db-trans:import-messages myDomain
```

In the translation directory of the project will be created a file named `db_myDomain.locale.db` for each enabled locale

Usage
-----

You can use these twig translation functions:

- `db_trans` will display database translations.
- `editable_db_trans` will display database translations for normal users, but:
  - if user has the `ROLE_TRANSLATOR` permission then the translation can be modified directly on the page (an icon will appear when the mouse is over the translation)
  - be ware that this function will surround the translated string with a _span_ element.

For both functions parameters are same as [trans twig filter](https://symfony.com/doc/current/reference/twig_reference.html#trans):

- message
- arguments (optional)
- domain (optional)
- locale (optional)

Both functions will try to translate appending the domainPrefix so the `{{ db_trans('some.mesage',{},'myDomain') }}` function will search for `some.mesage` key in the `db_myDomain` domain.

If this translation is not found it will fallback to the original domain translation (equivalent to `{{ 'some.mesage'| trans({},'myDomain') }}` )