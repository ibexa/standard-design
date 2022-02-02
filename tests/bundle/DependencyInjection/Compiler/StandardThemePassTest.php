<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\StandardDesign\DependencyInjection\Compiler;

use Ibexa\Bundle\StandardDesign\DependencyInjection\Compiler\StandardThemePass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Test StandardTheme Compiler pass.
 *
 * @see \Ibexa\Bundle\StandardDesign\DependencyInjection\Compiler\StandardThemePass
 */
class StandardThemePassTest extends AbstractCompilerPassTestCase
{
    /**
     * Data provider returning various Ibexa Design Lists configurations.
     */
    public function getDesignList()
    {
        return [
            [
                ['custom' => ['custom']],
                ['custom' => ['custom', 'standard']],
            ],
            [
                ['custom' => ['custom1', 'custom2']],
                ['custom' => ['custom1', 'custom2', 'standard']],
            ],
            [
                ['standard' => ['standard']],
                ['standard' => ['standard']],
            ],
            [
                ['empty' => []],
                ['empty' => ['standard']],
            ],
            [
                [
                    'design1' => ['theme1', 'theme2'],
                    'design2' => ['theme1', 'theme3'],
                ],
                [
                    'design1' => ['theme1', 'theme2', 'standard'],
                    'design2' => ['theme1', 'theme3', 'standard'],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getDesignList
     *
     * @param array $designList
     * @param array $expectedDesignList
     */
    public function testStandardThemeIsAppendedToEveryDesign(
        array $designList,
        array $expectedDesignList
    ) {
        $this->setParameter('ibexa.design.list', $designList);

        $this->compile();

        self::assertContainerBuilderHasParameter('ibexa.design.list', $expectedDesignList);
    }

    /**
     * Register the StandardTheme compiler pass under test.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new StandardThemePass());
    }
}

class_alias(StandardThemePassTest::class, 'EzSystems\Tests\EzPlatformStandardDesignBundle\DependencyInjection\Compiler\StandardThemePassTest');
