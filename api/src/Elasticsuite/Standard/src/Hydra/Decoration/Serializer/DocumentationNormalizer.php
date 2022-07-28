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

namespace Elasticsuite\Hydra\Decoration\Serializer;

use ApiPlatform\Core\Api\OperationType;
use ApiPlatform\Core\Hydra\Serializer\DocumentationNormalizer as BaseDocumentationNormalizer;
use ApiPlatform\Core\Metadata\Property\Factory\PropertyMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Property\Factory\PropertyNameCollectionFactoryInterface;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Resource\ResourceMetadata;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Allows to add extra data in Hydra API documentation.
 */
class DocumentationNormalizer implements NormalizerInterface
{
    public function __construct(
        private ResourceMetadataFactoryInterface $resourceMetadataFactory,
        private PropertyNameCollectionFactoryInterface $propertyNameCollectionFactory,
        private PropertyMetadataFactoryInterface $propertyMetadataFactory,
        private NormalizerInterface $decorated,
        private ?NameConverterInterface $nameConverter = null,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $documentation = $this->decorated->normalize($object, $format, $context);
        foreach ($object->getResourceNameCollection() as $resourceClass) {
            $resourceMetadata = $this->resourceMetadataFactory->create($resourceClass);
            $shortName = $resourceMetadata->getShortName();
            $prefixedShortName = $resourceMetadata->getIri() ?? "#$shortName";

            $this->updateHydraProperties($documentation, $resourceClass, $resourceMetadata, $shortName, $prefixedShortName, $context);
        }

        return $documentation;
    }

    /**
     * Update Hydra properties from the attributes get from ApiProperty.
     */
    private function updateHydraProperties(array &$documentation, string $resourceClass, ResourceMetadata $resourceMetadata, string $shortName, string $prefixedShortName, array $context): void
    {
        $classKey = array_search($shortName, array_column($documentation['hydra:supportedClass'] ?? [], 'hydra:title'), true);
        if (false === $classKey) {
            return;
        }

        $classes = [];
        foreach ($resourceMetadata->getCollectionOperations() as $operationName => $operation) {
            $inputMetadata = $resourceMetadata->getTypedOperationAttribute(OperationType::COLLECTION, $operationName, 'input', ['class' => $resourceClass], true);
            if (null !== $inputClass = $inputMetadata['class'] ?? null) {
                $classes[$inputClass] = true;
            }

            $outputMetadata = $resourceMetadata->getTypedOperationAttribute(OperationType::COLLECTION, $operationName, 'output', ['class' => $resourceClass], true);
            if (null !== $outputClass = $outputMetadata['class'] ?? null) {
                $classes[$outputClass] = true;
            }
        }

        /** @var string[] $classes */
        $classes = array_keys($classes);
        foreach ($classes as $class) {
            foreach ($this->propertyNameCollectionFactory->create($class, $this->getPropertyNameCollectionFactoryContext($resourceMetadata)) as $propertyName) {
                $propertyMetadata = $this->propertyMetadataFactory->create($class, $propertyName);
                if (null === $propertyMetadata->getAttribute('hydra:supportedProperty')
                    || !\is_array($propertyMetadata->getAttribute('hydra:supportedProperty'))
                ) {
                    continue;
                }

                if (true === $propertyMetadata->isIdentifier() && false === $propertyMetadata->isWritable()) {
                    continue;
                }

                if ($this->nameConverter) {
                    /** @var MetadataAwareNameConverter $nameConverter */
                    $nameConverter = $this->nameConverter;
                    $propertyName = $nameConverter->normalize($propertyName, $class, BaseDocumentationNormalizer::FORMAT, $context);
                }

                $propertyKey = array_search($propertyName, array_column($documentation['hydra:supportedClass'][$classKey]['hydra:supportedProperty'] ?? [], 'hydra:title'), true);
                if (false !== $propertyKey) {
                    $propertyDoc = array_replace_recursive(
                        $documentation['hydra:supportedClass'][$classKey]['hydra:supportedProperty'][$propertyKey],
                        $propertyMetadata->getAttribute('hydra:supportedProperty')
                    );
                    $documentation['hydra:supportedClass'][$classKey]['hydra:supportedProperty'][$propertyKey] = $propertyDoc;
                }
            }
        }
    }

    /**
     * Gets the context for the property name factory.
     */
    private function getPropertyNameCollectionFactoryContext(ResourceMetadata $resourceMetadata): array
    {
        $attributes = $resourceMetadata->getAttributes();
        $context = [];

        if (isset($attributes['normalization_context'][AbstractNormalizer::GROUPS])) {
            $context['serializer_groups'] = (array) $attributes['normalization_context'][AbstractNormalizer::GROUPS];
        }

        if (!isset($attributes['denormalization_context'][AbstractNormalizer::GROUPS])) {
            return $context;
        }

        if (isset($context['serializer_groups'])) {
            foreach ((array) $attributes['denormalization_context'][AbstractNormalizer::GROUPS] as $groupName) {
                $context['serializer_groups'][] = $groupName;
            }

            return $context;
        }

        $context['serializer_groups'] = (array) $attributes['denormalization_context'][AbstractNormalizer::GROUPS];

        return $context;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        /** @var BaseDocumentationNormalizer $decorated */
        $decorated = $this->decorated;

        return $decorated->supportsNormalization($data, $format, $context);
    }
}
