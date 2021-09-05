<?php

namespace App\Validation;

use App\Interfaces\ConstraintInterface;
use Symfony\Component\Validator\Constraints as Constraint;

class CustomerConstraints implements ConstraintInterface
{
    
    public static function getConstraints(): Constraint\Collection
    {
        return new Constraint\Collection([
            'fields' => [
                'name' => new Constraint\Optional([
                        new Constraint\Collection([
                        'fields' => [
                            'first' => [
                                new Constraint\Length(['max' => 255]),
                                new Constraint\Type(['type' => 'string']),
                            ],
                            'last' => [
                                new Constraint\Length(['max' => 255]),
                                new Constraint\Type(['type' => 'string']),
                            ]
                        ],
                        'allowExtraFields' => true,
                    ]),
                ]),
                'email' => new Constraint\Required([
                    new Constraint\Email()
                ]),
                'login' => new Constraint\Collection([
                    'fields' => [
                        'username' => [
                            new Constraint\Length(['max' => 255]),
                            new Constraint\Type(['type' => 'string']),
                        ]
                    ],
                    'allowExtraFields' => true,
                ]),
                'city' => new Constraint\Optional([
                    new Constraint\Length(['max' => 255]),
                    new Constraint\Type(['type' => 'string']),
                ]),
                'country' => new Constraint\Optional([
                    new Constraint\Length(['max' => 255]),
                    new Constraint\Type(['type' => 'string']),
                ]),
                'gender' => new Constraint\Optional([new Constraint\Choice(['male', 'female'])]),
                'phone' => new Constraint\Optional([
                    new Constraint\Length(['max' => 255]),
                    new Constraint\Type(['type' => 'string']),
                ])
            ],
            'allowExtraFields' => true,
        ]);
    }
}