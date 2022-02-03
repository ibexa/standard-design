<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\StandardDesign\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generate Configuration for Ibexa DXP Standard Design.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(IbexaStandardDesignExtension::EXTENSION_NAME);

        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->booleanNode('override_kernel_templates')
                    ->defaultFalse()
                    ->info('Enable this to prepend Kernel default template paths with @ibexadesign namespace')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

class_alias(Configuration::class, 'EzSystems\EzPlatformStandardDesignBundle\DependencyInjection\Configuration');
