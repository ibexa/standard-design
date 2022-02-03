<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\StandardDesign\DependencyInjection\Compiler;

use Ibexa\Bundle\StandardDesign\DependencyInjection\IbexaStandardDesignExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Compiler pass implemented to override Ibexa DXP Core default template paths defined in Container.
 */
class KernelOverridePass implements CompilerPassInterface
{
    /**
     * Load Standard Design configuration which overrides Ibexa DXP Core setup.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function process(ContainerBuilder $container)
    {
        $overrideTemplates = $container->getParameter(
            IbexaStandardDesignExtension::OVERRIDE_KERNEL_TEMPLATES_PARAM_NAME
        );
        if ($overrideTemplates) {
            $loader = new YamlFileLoader(
                $container,
                new FileLocator(__DIR__ . '/../../Resources/config')
            );
            $loader->load('override/ezpublish.yaml');
        }

        $this->setStandardThemeDirectories($container);
    }

    /**
     * Determine and append to standard theme Ibexa Core bundle views directory path.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    private function setStandardThemeDirectories(ContainerBuilder $container)
    {
        if (!$container->hasParameter('kernel.bundles_metadata')) {
            return;
        }

        $bundlesMetaData = $container->getParameter('kernel.bundles_metadata');
        $path = $bundlesMetaData['IbexaCoreBundle']['path'] ?? null;
        if (null === $path) {
            return;
        }

        $templatesPathMap = $container->hasParameter('ibexa.design.templates.path_map')
            ? $container->getParameter('ibexa.design.templates.path_map')
            : [];

        $templatesPathMap['standard'][] = $path . '/Resources/views';

        $container->setParameter('ibexa.design.templates.path_map', $templatesPathMap);
    }
}

class_alias(KernelOverridePass::class, 'EzSystems\EzPlatformStandardDesignBundle\DependencyInjection\Compiler\EzKernelOverridePass');
