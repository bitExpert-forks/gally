<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Gally to newer versions in the future.
 *
 * @package   Gally
 * @author    Gally Team <elasticsuite@smile.fr>
 * @copyright 2022-present Smile
 * @license   Open Software License v. 3.0 (OSL-3.0)
 */

declare(strict_types=1);

namespace Gally\Category\Tests\Api\Rest;

use Gally\Category\Model\Category;
use Gally\Test\AbstractEntityTest;
use Gally\User\Constant\Role;
use Gally\User\Model\User;

class CategoryTest extends AbstractEntityTest
{
    protected static function getFixtureFiles(): array
    {
        return [
            __DIR__ . '/../../fixtures/source_field.yaml',
            __DIR__ . '/../../fixtures/metadata.yaml',
            __DIR__ . '/../../fixtures/catalogs.yaml',
            __DIR__ . '/../../fixtures/categories.yaml',
        ];
    }

    protected function getEntityClass(): string
    {
        return Category::class;
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        User $user,
        array $data,
        int $responseCode = 201,
        ?string $message = null,
        string $validRegex = null
    ): void {
        // Category can't be created via api.
        $this->assertTrue(true);
    }

    public function createDataProvider(): iterable
    {
        return [
            [
                $this->getUser(Role::ROLE_CONTRIBUTOR),
                ['id' => 'one', 'name' => 'One'],
                403,
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getDataProvider(): iterable
    {
        $user = $this->getUser(Role::ROLE_ADMIN);

        return [
            [$this->getUser(Role::ROLE_CONTRIBUTOR), 'one', ['id' => 'one'], 403],
            [$user, 'one', ['id' => 'one'], 200],
            [$user, 'two', ['id' => 'two'], 200],
            [$user, 'missing', [], 404],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function deleteDataProvider(): iterable
    {
        $adminUser = $this->getUser(Role::ROLE_ADMIN);

        return [
            [$this->getUser(Role::ROLE_CONTRIBUTOR), 'one', 403],
            [$adminUser, 'one', 204],
            [$adminUser, 'missing', 404],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getCollectionDataProvider(): iterable
    {
        return [
            [$this->getUser(Role::ROLE_CONTRIBUTOR), 4, 403],
            [$this->getUser(Role::ROLE_ADMIN), 4, 200],
        ];
    }
}