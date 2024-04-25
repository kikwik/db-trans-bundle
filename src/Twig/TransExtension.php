<?php

namespace Kikwik\DbTransBundle\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TransExtension extends AbstractExtension
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var string
     */
    private $domainPrefix;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function __construct(Environment $twig, UrlGeneratorInterface $urlGenerator, AuthorizationCheckerInterface $authorizationChecker, string $domainPrefix)
    {
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
        $this->domainPrefix = $domainPrefix;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('db_trans', [$this, 'dbTrans'], ['is_safe' => ['html']]),
            new TwigFunction('editable_db_trans', [$this, 'editableDbTrans'], ['is_safe' => ['html']]),
        ];
    }

    public function dbTrans($message, $arguments = [], string $domain = null, string $locale = null, int $count = null)
    {
        if($this->twig->hasExtension('Symfony\Bridge\Twig\Extension\TranslationExtension'))
        {
            if (null === $domain) {
                $domain = 'messages';
            }
            $dbDomain = $this->domainPrefix.$domain;
            $trans = $this->twig->getExtension('Symfony\Bridge\Twig\Extension\TranslationExtension');
            $translatedValue = $trans->trans($message, $arguments, $dbDomain, $locale, $count);
            if($translatedValue == $message)
            {
                // fallback to file translation
                $translatedValue = $trans->trans($message, $arguments, $domain, $locale, $count);
            }
            return $translatedValue;
        }
        return $message;
    }

    public function editableDbTrans($message, $arguments = [], string $domain = null, string $locale = null, int $count = null)
    {
        $translatedValue = $this->dbTrans($message, $arguments, $domain, $locale, $count);
        if($this->authorizationChecker->isGranted('ROLE_TRANSLATOR'))
        {
            if (null === $domain) {
                $domain = 'messages';
            }
            if(!$translatedValue)
            {
                $translatedValue = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            }
            return sprintf('<span class="js-trans-message"><img class="js-trans-message-edit" data-url="%s" style="display: none;" src="/bundles/kikwikdbtrans/translate.svg"/> %s </span>',
                $this->urlGenerator->generate('kikwik_db_trans_bundle_translation_edit',['dbDomain'=>$this->domainPrefix.$domain, 'messageId'=>$message]),
                $translatedValue,
            );
        }
        return $translatedValue;
    }
}