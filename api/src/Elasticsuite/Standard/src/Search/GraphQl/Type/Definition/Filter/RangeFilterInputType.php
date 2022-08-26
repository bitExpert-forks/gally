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

namespace Elasticsuite\Search\GraphQl\Type\Definition\Filter;

use ApiPlatform\Core\GraphQl\Type\Definition\TypeInterface;
use Elasticsuite\GraphQl\Type\Definition\FilterInterface;
use Elasticsuite\Metadata\Repository\SourceFieldRepository;
use Elasticsuite\Search\Elasticsearch\Builder\Request\Query\Filter\FilterQueryBuilder;
use Elasticsuite\Search\Elasticsearch\Request\ContainerConfigurationInterface;
use Elasticsuite\Search\Elasticsearch\Request\QueryInterface;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class RangeFilterInputType extends InputObjectType implements TypeInterface, FilterInterface
{
    use FilterableFieldTrait;

    public const NAME = 'RangeFilterInput';

    public function __construct(
        private FilterQueryBuilder $filterQueryBuilder,
        private SourceFieldRepository $sourceFieldRepository,
    ) {
        $this->name = self::NAME;

        parent::__construct($this->getConfig());
    }

    public function getConfig(): array
    {
        return [
            'fields' => [
                'field' => ['type' => Type::nonNull(Type::string())],
                'from' => Type::string(),
                'to' => Type::string(),
            ],
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function validate(string $argName, mixed $inputData): array
    {
        $errors = [];

        $errors = array_merge($errors, $this->validateIsFilterable($inputData['field']));
        if (!isset($inputData['from']) && !isset($inputData['to'])) {
            $errors[] = "Filter argument {$argName}: At least 'from' or 'to' should be filled";
        }

        return $errors;
    }

    public function transformToElasticsuiteFilter(array $inputFilter, ContainerConfigurationInterface $containerConfig): QueryInterface
    {
        $conditions = [];
        foreach (['from', 'to'] as $condition) {
            if (isset($inputFilter[$condition])) {
                $conditions = array_merge($conditions, [$condition => $inputFilter[$condition]]);
            }
        }

        $filterData = [
            $inputFilter['field'] => $conditions,
        ];

        return $this->filterQueryBuilder->create($containerConfig, $filterData);
    }
}