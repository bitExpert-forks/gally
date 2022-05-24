<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Smile ElasticSuite to newer
 * versions in the future.
 *
 * @package   Acme\Example
 * @author    ElasticSuite Team <elasticsuite@smile.fr>
 * @copyright 2022 Smile
 * @license   Licensed to Smile-SA. All rights reserved. No warranty, explicit or implicit, provided.
 *            Unauthorized copying of this file, via any medium, is strictly prohibited.
 */

declare(strict_types=1);

namespace Acme\Example\Example\Model;

use Acme\Example\Example\Resolver\DummyCollectionResolver;
use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;

#[
    ApiResource(
        collectionOperations: [],
        graphql: [
            'search' => [
                'pagination_type' => 'page',
                'collection_query' => DummyCollectionResolver::class,
                'args' => [
                    'indexName' => ['type' => 'String!', 'description' => 'Index name.'],
                    'search' => ['type' => 'String'],
                    'pageSize' => ['type' => 'Int'],
                    'currentPage' => ['type' => 'Int'],
                    'filter' => ['type' => '[ExampleFieldFilterInput]'],
                ],
            ]
        ],
        itemOperations: [
            'get' => [
                'controller' => NotFoundAction::class,
                'read' => false,
                'output' => false,
            ],
        ],
    ),
]

class ExampleResultDocument
{
    #[ApiProperty(
        identifier: true
    )]
    private string $id;

    /**
     * @var array
     */
    private array $data;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }
}