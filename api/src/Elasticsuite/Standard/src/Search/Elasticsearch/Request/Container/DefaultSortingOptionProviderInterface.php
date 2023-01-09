<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Smile ElasticSuite to newer
 * versions in the future.
 *
 * @package   Elasticsuite
 * @author    ElasticSuite Team <elasticsuite@smile.fr>
 * @copyright 2022 Smile
 * @license   Licensed to Smile-SA. All rights reserved. No warranty, explicit or implicit, provided.
 *            Unauthorized copying of this file, via any medium, is strictly prohibited.
 */

declare(strict_types=1);

namespace Elasticsuite\Search\Elasticsearch\Request\Container;

use Elasticsuite\Search\Elasticsearch\Request\ContainerConfigurationInterface;

/**
 * Default sorting option provider interface for search requests.
 */
interface DefaultSortingOptionProviderInterface
{
    /**
     * Returns aggregations configured in the search container, and according to currently applied query and filters.
     *
     * @param ContainerConfigurationInterface $containerConfig search container configuration
     */
    public function getSortingOption(ContainerConfigurationInterface $containerConfig): array;
}