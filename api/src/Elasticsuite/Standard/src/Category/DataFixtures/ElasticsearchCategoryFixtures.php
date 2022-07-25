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

namespace Elasticsuite\Category\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Elasticsuite\Fixture\Service\ElasticsearchFixtures;

class ElasticsearchCategoryFixtures extends Fixture
{
    public function __construct(
        private ElasticsearchFixtures $elasticsearchFixtures,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->elasticsearchFixtures->loadFixturesIndexFiles([__DIR__ . '/fixtures/categories_indices.json']);
        $this->elasticsearchFixtures->loadFixturesDocumentFiles([__DIR__ . '/fixtures/categories_documents.json']);
    }
}