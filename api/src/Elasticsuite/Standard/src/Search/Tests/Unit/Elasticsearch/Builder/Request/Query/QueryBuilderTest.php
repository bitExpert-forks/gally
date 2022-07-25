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

namespace Elasticsuite\Search\Tests\Unit\Elasticsearch\Builder\Request\Query;

use Elasticsuite\Search\Elasticsearch\Builder\Request\Query\QueryBuilder;
use Elasticsuite\Search\Elasticsearch\Request\QueryFactory;
use Elasticsuite\Search\Elasticsearch\Request\QueryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Search request query builder test case.
 */
class QueryBuilderTest extends KernelTestCase
{
    /**
     * Test creating a query from a fulltext search and filters.
     */
    public function testCreateQuery(): void
    {
        $builder = new QueryBuilder(
            $this->getQueryFactory()/*,
            $this->getFulltextQueryBuilder(),
            $this->getFilterQueryBuilder(),
            $this->getSearchContext() */
        );

        $query = $builder->createQuery(
            // $this->getContainerConfiguration(),
            'test'/*,
            ['filter'],
            SpellcheckerInterface::SPELLING_TYPE_EXACT */
        );

        $this->assertInstanceOf(QueryInterface::class, $query);
        $this->assertEquals(QueryInterface::TYPE_FILTER, $query->getType());

        /** @var \Elasticsuite\Search\Elasticsearch\Request\Query\Filtered $query */
        // TODO: implement fulltext queries builder first.
        $this->assertNull($query->getQuery());
        // $this->assertInstanceOf(QueryInterface::class, $query->getQuery());
        // $this->assertEquals('fulltextQuery', $query->getQuery()->getType());

        // TODO: implement filter queries builder first.
        $this->assertNull($query->getFilter());
        // $this->assertInstanceOf(QueryInterface::class, $query->getFilter());
        // $this->assertEquals('filterQuery', $query->getFilter()->getType());
    }

    /*
     * Mocks the search context.
     */
    /*
    private function getSearchContext(): MockObject
    {
        return $this->getMockBuilder(ContextInterface::class)->getMock();
    }
    */

    /*
     * Mocks the container configuration.
     */
    /*
    private function getContainerConfiguration(): MockObject
    {
        $containerConfiguration = $this->getMockBuilder(ContainerConfigurationInterface::class)->getMock();

        $containerConfiguration->method('getFilters')->willReturn([]);

        return $containerConfiguration;
    }
    */

    /**
     * Get the query factory used by the tested builder.
     */
    private function getQueryFactory(): QueryFactory
    {
        return static::getContainer()->get(QueryFactory::class);
    }

    /*
     * Mocks the fulltext query builder.
     */
    /*
    private function getFulltextQueryBuilder(): MockObject
    {
        return $this->getQueryBuilder(\Elasticsuite\Search\Request\Query\Fulltext\QueryBuilder::class, 'fulltextQuery');
    }
    */

    /*
     * Mocks the filters query builder.
     */
    /*
    private function getFilterQueryBuilder(): MockObject
    {
        return $this->getQueryBuilder(\Elasticsuite\Search\Request\Query\Filter\QueryBuilder::class, 'filterQuery');
    }
    */

    /*
     * Mock a query builder that creates query with the indicated type.
     *
     * @param string $class mocked class name
     * @param string $name  mock returned query type
     */
    /*
    private function getQueryBuilder(string $class, string $name): MockObject
    {
        $query = $this->getMockBuilder(QueryInterface::class)->getMock();
        $query->method('getType')->willReturn($name);

        $queryBuilder = $this->getMockBuilder($class)->disableOriginalConstructor()->getMock();
        $queryBuilder->method('create')->willReturn($query);

        return $queryBuilder;
    }
    */
}