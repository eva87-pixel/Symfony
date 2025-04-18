<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class Antispam
 *
 * Cette contrainte est utilisée pour vérifier qu'un champ ne contient que des lettres.
 * Elle peut être appliquée sur les propriétés d'une entité via des attributs PHP (annotations).
 *
 * @package App\Validator
 */
#[\Attribute]
class Antispam extends Constraint
{
    /**
     * Le message d'erreur à afficher si la contrainte n'est pas respectée.
     *
     * @var string
     */
    public $message = "Votre champ ne doit contenir que des lettres";
}