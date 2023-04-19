<?php

namespace Kikwik\DbTransBundle\Loader;

use Kikwik\DbTransBundle\Entity\Translation;
use Kikwik\DbTransBundle\Repository\TranslationRepository;
use Symfony\Component\Translation\Exception\InvalidResourceException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;

class DbLoader implements LoaderInterface
{
    /**
     * @var TranslationRepository
     */
    private $translationRepository;

    public function __construct(TranslationRepository $translationRepository)
    {
        $this->translationRepository = $translationRepository;
    }

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
        $messages = [
            $domain => []
        ];

        $translations = $this->translationRepository->findByDomainAndLocale($domain, $locale);
        foreach($translations as $translation)
        {
            /** @var Translation $translation */
            $messages[$domain][$translation->getMessageId()] = $translation->getMessage();
        }

        return new MessageCatalogue($locale, $messages);
    }

}