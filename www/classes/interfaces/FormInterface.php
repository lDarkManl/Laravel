<?php

namespace classes\interfaces;

interface FormInterface
{
    public function addField(\classes\FormField $field);

    public function populate(array $data): void;

    public function isValid(): bool;

    public function submit($address): bool;
}