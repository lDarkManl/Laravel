<?php

namespace classes\interfaces;

interface ValidatorInterface
{
    public function validate($field): bool;

    public function showError(): string;
}