<?php

namespace classes\interfaces;

interface FormInterface
{
    public function addFiled(FormField $field);

    public function populate(array $data): void;

    public function isValid(): bool;

    public function submit(): bool;
}