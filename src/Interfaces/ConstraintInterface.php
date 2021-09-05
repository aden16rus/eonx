<?php

namespace App\Interfaces;

use Symfony\Component\Validator\Constraints\Collection;

interface ConstraintInterface
{
    public static function getConstraints(): Collection;
}