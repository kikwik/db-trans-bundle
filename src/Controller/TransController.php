<?php

namespace Kikwik\DbTransBundle\Controller;


use Doctrine\Persistence\ManagerRegistry;
use Kikwik\DbTransBundle\Entity\Translation;
use Kikwik\DbTransBundle\Form\TranslationFormType;
use Kikwik\DbTransBundle\Interfaces\TranslationEntityInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class TransController extends AbstractController
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;
    /**
     * @var KernelInterface
     */
    private $kernel;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var array
     */
    private $locales;

    public function __construct(ManagerRegistry $doctrine, KernelInterface $kernel, UrlGeneratorInterface $urlGenerator, array $locales)
    {
        $this->doctrine = $doctrine;
        $this->kernel = $kernel;
        $this->urlGenerator = $urlGenerator;
        $this->locales = $locales;
    }


    public function edit(string $dbDomain, string $messageId, Request $request)
    {
        $transClass = Translation::class;
        $transRepo = $this->doctrine->getRepository($transClass);

        $translations = $transRepo->findBy(['messageId'=>$messageId]);
        $action = $this->urlGenerator->generate('kikwik_db_trans_bundle_translation_edit',['dbDomain' =>$dbDomain, 'messageId' =>$messageId]);
        $form = $this->createTranslationsForm($this->locales, $dbDomain, $messageId, $translations, $action);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            foreach($data as $translation)
            {
                if($translation instanceof TranslationEntityInterface)
                {
                    if($form[$translation->getLocale()]['elimina']->getData())
                    {
                        $this->doctrine->getManager()->remove($translation);
                    }
                    else
                    {
                        $this->doctrine->getManager()->persist($translation);
                    }
                }
            }
            $this->doctrine->getManager()->flush();

            if($form->get('saveAndUpdate')->isClicked())
            {
                // clear the cache
                $application = new Application($this->kernel);
                $application->setAutoExit(false);
                $application->run(new ArrayInput(['command' => 'cache:clear']), new NullOutput());

                return new Response('REFRESH');
            }

            return new Response('OK');
        }


        return $this->render('@KikwikDbTrans/edit.html.twig',[
            'transKey' => $messageId,
            'form' => $form->createView(),
        ]);
    }

    private function createTranslationsForm(array $locales, string $transDomain, string $transKey, array $translations, string $action)
    {
        // fill the data array with objects from database o new objects
        $data = [];
        foreach($locales as $locale)
        {
            foreach($translations as $translation)
            {
                if($translation->getLocale() == $locale)
                {
                    // object from datatbase
                    $data[$locale] = $translation;
                    break;
                }
            }
            if(!isset($data[$locale]))
            {
                // new object
                $data[$locale] = new Translation();
                $data[$locale]->setLocale($locale);
                $data[$locale]->setDomain($transDomain);
                $data[$locale]->setMessageId($transKey);
            }
        }

        // create the form (one field for each locale)
        $formBuilder = $this->createFormBuilder($data,['attr'=>['novalidate'=>'novalidate']])
            ->setAction($action)
            ->setMethod('POST')
        ;
        foreach($locales as $locale)
        {
            $formBuilder->add($locale, TranslationFormType::class);
        }
        $formBuilder->add('save',SubmitType::class,[
            'attr'=>['class'=>'btn btn-success'],
            'row_attr'=>['style'=>'display:inline-block;margin-right:1rem;'],
            'translation_domain'=>'KikwikDbTransBundle',
        ]);
        $formBuilder->add('saveAndUpdate',SubmitType::class,[
            'attr'=>['class'=>'btn btn-success'],
            'row_attr'=>['style'=>'display: inline-block;'],
            'translation_domain'=>'KikwikDbTransBundle',
        ]);

        return $formBuilder->getForm();
    }
}