<?php

namespace Rz\MediaBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Rz\MediaBundle\DependencyInjection\Compiler\OverrideServiceCompilerPass;
use Rz\MediaBundle\DependencyInjection\Compiler\AddProviderCompilerPass;

class RzMediaBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new OverrideServiceCompilerPass());
        $container->addCompilerPass(new AddProviderCompilerPass());
    }
}
