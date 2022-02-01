<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\StandardDesign\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Ensure Standard theme is a fallback of every design.
 */
class StandardThemePass implements CompilerPassInterface
{
    /**
     * Process defined designs and themes list and append standard theme if missing.
     *
     * Standard theme defines default core templates used for rendering, so when different design
     * is used, missing template still needs to be rendered using fallback.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('ibexa.design.list')) {
            return;
        }

        $designList = $container->getParameter('ibexa.design.list');
        foreach ($designList as $designName => $themes) {
            if (!in_array('standard', $themes)) {
                $designList[$designName][] = 'standard';
            }
        }
        $container->setParameter('ibexa.design.list', $designList);
    }
}

class_alias(StandardThemePass::class, 'EzSystems\EzPlatformStandardDesignBundle\DependencyInjection\Compiler\StandardThemePass');
