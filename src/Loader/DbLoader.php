<?php

namespace Kikwik\DbTransBundle\Loader;

use Doctrine\Persistence\ManagerRegistry;
use Kikwik\DbTransBundle\Entity\Translation;
use Kikwik\DbTransBundle\Interfaces\TranslationEntityInterface;
use Symfony\Component\Translation\Exception\InvalidResourceException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;

class DbLoader implements LoaderInterface
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
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

        $transClass = Translation::class;
        $transRepo = $this->doctrine->getRepository($transClass);

        $translations = $transRepo->findBy(['domain'=>$domain, 'locale'=>$locale]);
        foreach($translations as $translation)
        {
            /** @var TranslationEntityInterface $translation */
            $messages[$domain][$translation->getMessageId()] = $translation->getMessage();
        }

        return new MessageCatalogue($locale, $messages);
    }

}