<?php

namespace Magmell\Contao\Passwordless\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Magmell\Contao\Passwordless\PasswordlessBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(PasswordlessBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
        ];
    }
}
