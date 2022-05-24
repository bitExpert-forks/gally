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

namespace Elasticsuite\Search\Tests\Api\Rest;

use Elasticsuite\Standard\src\Test\AbstractTest;

class FacetConfigurationTest extends AbstractTest
{
    public static function setUpBeforeClass(): void
    {
        static::loadFixture([
            __DIR__ . '/../../fixtures/catalogs.yaml',
            __DIR__ . '/../../fixtures/categories.yaml',
            __DIR__ . '/../../fixtures/metadata.yaml',
            __DIR__ . '/../../fixtures/source_field.yaml',
        ]);
    }

    protected function getApiPath(): string
    {
        return '/facet_configurations';
    }

    /**
     * @dataProvider getCollectionBeforeDataProvider
     */
    public function testGetCollectionBefore(?int $categoryId, array $elements): void
    {
        $this->testGetCollection($categoryId, $elements);
    }

    protected function getCollectionBeforeDataProvider(): array
    {
        return [
            [
                null,
                [
                    ['sourceField' => 3],
                    ['sourceField' => 4],
                    ['sourceField' => 5],
                ],
            ],
            [
                1,
                [
                    ['sourceField' => 3, 'category' => 1],
                    ['sourceField' => 4, 'category' => 1],
                    ['sourceField' => 5, 'category' => 1],
                ],
            ],
            [
                2,
                [
                    ['sourceField' => 3, 'category' => 2],
                    ['sourceField' => 4, 'category' => 2],
                    ['sourceField' => 5, 'category' => 2],
                ],
            ],
        ];
    }

    /**
     * @dataProvider updateDataProvider
     * @depends testGetCollectionBefore
     */
    public function testUpdateValue(string $id, array $newData)
    {
        $this->requestRest('PUT', "{$this->getApiPath()}/$id", $newData);
        $this->assertResponseIsSuccessful();
    }

    protected function updateDataProvider(): array
    {
        return [
            ['3-0', ['coverageRate' => 0]],
            ['3-1', ['coverageRate' => 10]],
            ['4-1', ['coverageRate' => 10]],
            ['3-2', ['coverageRate' => 20]],
        ];
    }

    /**
     * @dataProvider getCollectionAfterDataProvider
     * @depends testUpdateValue
     */
    public function testGetCollectionAfter(?int $categoryId, array $items): void
    {
        $this->testGetCollection($categoryId, $items);
    }

    protected function getCollectionAfterDataProvider(): array
    {
        return [
            [
                null,
                [
                    ['sourceField' => 3, 'coverageRate' => 0],
                    ['sourceField' => 4],
                    ['sourceField' => 5],
                ],
            ],
            [
                1,
                [
                    ['sourceField' => 3, 'category' => 1, 'coverageRate' => 10, 'defaultCoverageRate' => 0],
                    ['sourceField' => 4, 'category' => 1, 'coverageRate' => 10],
                    ['sourceField' => 5, 'category' => 1, 'coverageRate' => 90],
                ],
            ],
            [
                2,
                [
                    ['sourceField' => 3, 'category' => 2, 'coverageRate' => 20, 'defaultCoverageRate' => 0],
                    ['sourceField' => 4, 'category' => 2, 'coverageRate' => 90],
                    ['sourceField' => 5, 'category' => 2, 'coverageRate' => 90],
                ],
            ],
        ];
    }

    protected function testGetCollection(?int $categoryId, array $items): void
    {
        $query = $categoryId ? "category=$categoryId" : '';
        $response = $this->requestRest('GET', $this->getApiPath() . '?' . $query . '&page=1', []);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(
            [
                '@context' => '/contexts/FacetConfiguration',
                '@id' => '/facet_configurations',
                '@type' => 'hydra:Collection',
                'hydra:totalItems' => 5,
            ]
        );

        $responseData = $response->toArray();

        foreach ($items as $item) {
            $expectedItem = $this->completeContent($item);
            $item = $this->getById($expectedItem['id'], $responseData['hydra:member']);
            $this->assertEquals($expectedItem, $item);
        }
    }

    protected function completeContent(array $data): array
    {
        $sourceFieldId = $data['sourceField'];
        $categoryId = $data['category'] ?? 0;
        unset($data['sourceField']);
        unset($data['category']);
        $id = implode('-', [$sourceFieldId, $categoryId]);

        $baseData = [
            '@id' => "/facet_configurations/$id",
            '@type' => 'FacetConfiguration',
            'id' => $id,
            'sourceField' => "/source_fields/$sourceFieldId",
            'displayMode' => 'auto',
            'coverageRate' => 90,
            'maxSize' => 10,
            'sortOrder' => 'result_count',
            'isRecommendable' => false,
            'isVirtual' => false,
            'defaultDisplayMode' => 'auto',
            'defaultMaxSize' => 10,
            'defaultCoverageRate' => 90,
            'defaultSortOrder' => 'result_count',
            'defaultIsRecommendable' => false,
            'defaultIsVirtual' => false,
        ];

        if ($categoryId) {
            $baseData['category'] = "/categories/$categoryId";
        }

        return array_merge($baseData, $data);
    }

    protected function getById(string $id, array $list): ?array
    {
        foreach ($list as $element) {
            if ($id === $element['id']) {
                return $element;
            }
        }

        return null;
    }
}