<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\StandardDesign\DependencyInjection;

use Ibexa\Bundle\StandardDesign\DependencyInjection\IbexaStandardDesignExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class EzPlatformStandardDesignExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions(): array
    {
        return [
            new IbexaStandardDesignExtension(),
        ];
    }

    public function testExtensionPrependsStandardDesignSettings()
    {
        $this->load();

        self::assertContains(
            [
                'design_list' => [
                    'standard' => ['standard'],
                ],
            ],
            $this->container->getExtensionConfig('ibexa_design_engine')
        );
    }
}

class_alias(EzPlatformStandardDesignExtensionTest::class, 'EzSystems\Tests\EzPlatformStandardDesignBundle\DependencyInjection\EzPlatformStandardDesignExtensionTest');
