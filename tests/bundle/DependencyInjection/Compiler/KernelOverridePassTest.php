<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\StandardDesign\DependencyInjection\Compiler;

use Ibexa\Bundle\StandardDesign\DependencyInjection\Compiler\KernelOverridePass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Test overriding Ibexa Core setup for templates with Ibexa Design.
 *
 * @see \Ibexa\Bundle\StandardDesign\DependencyInjection\Compiler\KernelOverridePass
 */
class KernelOverridePassTest extends AbstractCompilerPassTestCase
{
    public function getTemplatesPathMap()
    {
        return [
            [[]],
            [['standard' => ['/other/path']]],
            [['custom' => ['/another/path']]],
        ];
    }

    /**
     * Register the StandardTheme compiler pass under test.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->setParameter('ibexa.design.standard.override_kernel_templates', true);

        $container->addCompilerPass(new KernelOverridePass());
    }

    /**
     * @dataProvider getTemplatesPathMap
     *
     * @param array $templatesPathMap
     */
    public function testKernelViewsDirectoryIsMappedToStandardTheme(array $templatesPathMap)
    {
        $this->setParameter('ibexa.design.templates.path_map', $templatesPathMap);
        $this->setParameter(
            'kernel.bundles_metadata',
            [
                'IbexaCoreBundle' => [
                    'path' => '/some/path',
                ],
            ]
        );

        $this->container->compile();

        $templatesPathMap['standard'][] = '/some/path/Resources/views';

        self::assertContainerBuilderHasParameter(
            'ibexa.design.templates.path_map',
            $templatesPathMap
        );
    }

    public function testKernelTemplateNamesHaveEzDesignPrefix()
    {
        $this->container->compile();

        $parameters = $this->container->getParameterBag()->all();
        foreach ($parameters as $parameterId => $parameterValue) {
            $templates = [];

            if (is_string($parameterValue)) {
                $templates[] = $parameterValue;
            } elseif (is_array($parameterValue)) {
                foreach ($parameterValue as $nestedValues) {
                    if (!isset($nestedValues['template'])) {
                        continue;
                    }
                    $templates[] = $nestedValues['template'];
                }
            }

            foreach ($templates as $templatePath) {
                self::assertStringStartsWith(
                    '@ibexadesign/',
                    $templatePath,
                    "Parameter '{$parameterId}' template(s) doesn't start with '@ibexadesign' prefix"
                );
            }
        }
    }
}

class_alias(KernelOverridePassTest::class, 'EzSystems\Tests\EzPlatformStandardDesignBundle\DependencyInjection\Compiler\EzKernelOverridePassTest');
