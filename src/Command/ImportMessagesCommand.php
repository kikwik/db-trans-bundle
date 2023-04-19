<?php

namespace Kikwik\DbTransBundle\Command;

use Doctrine\Persistence\ManagerRegistry;
use Kikwik\DbTransBundle\Entity\Translation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Translation\MessageCatalogueInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ImportMessagesCommand extends Command
{
    protected static $defaultName = 'kikwik:db-trans:import-messages';
    protected static $defaultDescription = 'Import messages from translation file';
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var ManagerRegistry
     */
    private $doctrine;
    /**
     * @var string
     */
    private $translationDir;
    /**
     * @var string
     */
    private $domainPrefix;
    /**
     * @var array
     */
    private $locales;

    public function __construct(TranslatorInterface $translator, ManagerRegistry $doctrine, KernelInterface $kernel, string $domainPrefix, array $locales)
    {
        parent::__construct();
        $this->translator = $translator;
        $this->doctrine = $doctrine;
        $this->translationDir = $kernel->getContainer()->getParameter('translator.default_path');
        $this->domainPrefix = $domainPrefix;
        $this->locales = $locales;
    }

    protected function configure()
    {
        $this->addArgument('domain',InputArgument::OPTIONAL, 'The domain to import', 'messages');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $domain = $input->getArgument('domain');
        $io->note('Importing Translations for '.$domain. ' domain');

        foreach($this->locales as $locale)
        {
            $dbDomain = $this->domainPrefix.$domain;

            $transClass = Translation::class;
            $transRepo = $this->doctrine->getRepository($transClass);

            /** @var MessageCatalogueInterface $catalogue */
            $catalogue = $this->translator->getCatalogue($locale);
            $messages = $catalogue->all($domain);
            $table = new Table($output);
            $table->setHeaders(['messageId', 'locale', 'message']);
            $addCount = 0;
            $updateCount = 0;
            foreach($messages as $messageId => $message)
            {
                $table->addRow([$messageId, $locale, $this->shortString($message)]);
                $translation = $transRepo->findOneBy(['domain'=>$dbDomain, 'locale' => $locale, 'messageId' => $messageId]);
                if(!$translation)
                {
                    $translation = new $transClass();
                    $translation->setDomain($dbDomain);
                    $translation->setLocale($locale);
                    $translation->setMessageId($messageId);
                    $addCount++;
                }
                else
                {
                    $updateCount++;
                }
                $translation->setMessage($message);
                $this->doctrine->getManager()->persist($translation);
                $this->doctrine->getManager()->flush();

            }
            $table->render();
            $io->writeln('');

            $localeFile = $this->translationDir.'/'.$dbDomain.'.'.$locale.'.db';
            $io->writeln('creating empty file: <info>'.$localeFile.'</info>');
            if(!is_file($localeFile))
            {
                @touch($localeFile);
            }

            $io->success(sprintf('Translation in domain %s for locale %s: %d new, %d updated', $dbDomain, $locale, $addCount, $updateCount));
        }

        $command = $this->getApplication()->find('cache:clear');
        $command->run(new ArgvInput(), $output);

        return Command::SUCCESS;
    }

    private function shortString($value, $max = 70)
    {
        $short = substr($value,0,$max);
        return strlen($value)>$max
            ? $short.'...'
            : $short;
    }
}