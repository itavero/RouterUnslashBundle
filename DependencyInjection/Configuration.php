<?php

/**
 * This file is part of the Router Unslash Bundle.
 *
 * Copyright (c) 2013, Arno Moonen <info@arnom.nl>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @author Arno Moonen <info@arnom.nl>
 * @copyright Copyright (c) 2013, Arno Moonen <info@arnom.nl>
 */

namespace AMNL\RouterUnslashBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('amnl_router_unslash');

        $rootNode
                ->children()
                    ->booleanNode('permanent')->defaultFalse()->end()
                    ->booleanNode('public')->defaultTrue()->end()
                    ->integerNode('maxage')->defaultValue(1800)->end()
                    ->integerNode('smaxage')->defaultValue(21600)->end()
                    ->end()
                ->end();

        return $treeBuilder;
    }

}