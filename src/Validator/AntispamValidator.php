<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class AntispamValidator
 *
 * Ce validateur vérifie que la valeur passée ne contient que des lettres.
 * S'il y a des caractères non alphabétiques, une violation est ajoutée avec le message défini dans la contrainte.
 *
 * @package App\Validator
 */
class AntispamValidator extends ConstraintValidator
{
    /**
     * Valide la contrainte Antispam.
     *
     * Cette méthode teste si la valeur à valider ne contient que des lettres (a-z, A-Z).
     * Si la valeur ne correspond pas à l'expression régulière /^[a-zA-Z]+$/,
     * une violation est ajoutée avec le message d'erreur spécifié dans la contrainte.
     *
     * @param mixed $value La valeur à valider
     * @param Constraint $constraint L'instance de la contrainte Antispam
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!preg_match('/^[a-zA-Z]+$/', $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameters(['%string%' => $value])
                ->addViolation();
        }
    }
}