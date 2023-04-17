<?php

namespace Kikwik\DbTransBundle\Loader;

use Symfony\Component\Translation\Exception\InvalidResourceException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;

class DbLoader implements LoaderInterface
{
    /**
     * Loads a locale.
     *
     * @param mixed  $resource A resource
     * @param string $locale   A locale
     * @param string $domain   The domain
     *
     * @return MessageCatalogue
     *
     * @throws NotFoundResourceException when the resource cannot be found
     * @throws InvalidResourceException  when the resource cannot be loaded
     */
    public function load($resource, string $locale, string $domain = 'messages')
    {
        // TODO: load messages for $locale and $domain from db
        // example:
        $messages = [
            $domain => [
                'company.sito'=>'wu.wu.wu.'.$locale,
                'company.mail'=>'me@site.'.$locale,
            ]
        ];

        $catalogue = new MessageCatalogue($locale, $messages);

        return $catalogue;
    }

}