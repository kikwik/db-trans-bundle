<?php

namespace Kikwik\DbTransBundle\Entity;

use Kikwik\DbTransBundle\Interfaces\TranslationEntityInterface;

class Translation implements TranslationEntityInterface
{
    /**************************************/
    /* PROPERTIES                         */
    /**************************************/

    /**
     * Unique id of this document.
     *
     * @var int
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $domain;

    /**
     *
     * @var string
     */
    protected $locale;

    /**
     *
     * @var string
     */
    protected $messageId;

    /**
     *
     * @var string
     */
    protected $message;



    /**************************************/
    /* CUSTOM METHODS                     */
    /**************************************/


    /**************************************/
    /* GETTERS & SETTERS                  */
    /**************************************/

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     * @return Translation
     */
    public function setDomain(string $domain): TranslationEntityInterface
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * @return Translation
     */
    public function setLocale(string $locale): TranslationEntityInterface
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessageId(): string
    {
        return $this->messageId;
    }

    /**
     * @param string $messageId
     * @return Translation
     */
    public function setMessageId(string $messageId): TranslationEntityInterface
    {
        $this->messageId = $messageId;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Translation
     */
    public function setMessage(?string $message): TranslationEntityInterface
    {
        $this->message = $message;
        return $this;
    }




}