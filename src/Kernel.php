<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * Class Kernel
 *
 * Le Kernel de l'application étend le Kernel de Symfony (BaseKernel)
 * et utilise le MicroKernelTrait pour simplifier la configuration de l'application.
 *
 * @package App
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}