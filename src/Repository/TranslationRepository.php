<?php

namespace Kikwik\DbTransBundle\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kikwik\DbTransBundle\Entity\Translation;

class TranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Translation::class);
    }

    /**
     * @param string $domain
     * @param string $locale
     * @return mixed
     */
    public function findByDomainAndLocale(string $domain, string $locale)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.domain = :domain')->setParameter('domain',$domain)
            ->andWhere('t.locale = :locale')->setParameter('locale',$locale)
            ->getQuery()
            ->getResult();
    }
}