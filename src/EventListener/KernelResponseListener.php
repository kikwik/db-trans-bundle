<?php

namespace Kikwik\DbTransBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class KernelResponseListener
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        if($this->authorizationChecker->isGranted('ROLE_TRANSLATOR'))
        {
            /** @var Response $response */
            $response = $event->getResponse();

            $styleVersion = filemtime(__DIR__.'/../Resources/public/trans.css');
            $scriptVersion = filemtime(__DIR__.'/../Resources/public/trans.js');
            $transContent = <<<EOD
<div class="js-trans-modal">
  <div class="js-trans-modal-content">
    
  </div>
</div>
<link rel="stylesheet" href="/bundles/kikwikdbtrans/trans.css?v=$styleVersion"/>
<script src="/bundles/kikwikdbtrans/trans.js?v=$scriptVersion"></script>

EOD;

            $content = $response->getContent();
            if(strpos($content, '</body>') !== false)
            {
                $content = str_replace('</body>',$transContent.'</body>', $content);
                $response->setContent($content);
            }

        }
    }
}