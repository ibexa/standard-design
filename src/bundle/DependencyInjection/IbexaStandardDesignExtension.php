<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\StandardDesign\DependencyInjection;

use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Yaml\Yaml;

class IbexaStandardDesignExtension extends Extension implements PrependExtensionInterface
{
    public const string EXTENSION_NAME = 'ibexa_standard_design';

    public const string OVERRIDE_KERNEL_TEMPLATES_PARAM_NAME = 'ibexa.design.standard.override_kernel_templates';

    public function getAlias(): string
    {
        return self::EXTENSION_NAME;
    }

    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(
            static::OVERRIDE_KERNEL_TEMPLATES_PARAM_NAME,
            $config['override_kernel_templates']
        );
    }

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @throws \Exception
     */
    public function prepend(ContainerBuilder $container): void
    {
        $this->prependIbexaDesignSettings($container);
    }

    /**
     * Prepend settings for the given external extension.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder
     */
    private function prependIbexaDesignSettings(ContainerBuilder $containerBuilder): void
    {
        $configFile = __DIR__ . '/../Resources/config/extension/ibexa_design_engine.yaml';
        $config = Yaml::parseFile($configFile);
        $containerBuilder->prependExtensionConfig('ibexa_design_engine', $config);
        $containerBuilder->addResource(new FileResource($configFile));
    }
}
