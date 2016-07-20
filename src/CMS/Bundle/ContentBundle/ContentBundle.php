<?php

namespace CMS\Bundle\ContentBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use CMS\Bundle\ContentBundle\DependencyInjection\Compiler\DoctrineEntityListenerPass;

class ContentBundle extends Bundle
{

	public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DoctrineEntityListenerPass());
    }

}
