<?php

namespace Kikwik\DbTransBundle\Interfaces;

interface TranslationEntityInterface
{
    public function getId(): int;

    public function getDomain(): string;
    public function setDomain(string $domain): TranslationEntityInterface;

    public function getLocale(): string;
    public function setLocale(string $locale): TranslationEntityInterface;

    public function getMessageId(): string;
    public function setMessageId(string $messageId): TranslationEntityInterface;

    public function getMessage(): ?string;
    public function setMessage(?string $message): TranslationEntityInterface;
}