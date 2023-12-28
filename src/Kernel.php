<?php

namespace App;

use phpDocumentor\Reflection\Types\Parent_;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function __construct(string $environment, bool $debug)
    {
        \date_default_timezone_set("Europe/Paris");

        parent::__construct($environment , $debug);
    }
}
