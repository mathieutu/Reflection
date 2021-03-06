<?php
declare(strict_types = 1);

namespace Innmind\Reflection\ExtractionStrategy;

use Innmind\Reflection\{
    ExtractionStrategyInterface,
    Exception\LogicException,
    Visitor\AccessProperty
};

class ReflectionStrategy implements ExtractionStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports($object, string $property): bool
    {
        try {
            (new AccessProperty)($object, $property);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function extract($object, string $property)
    {
        if (!$this->supports($object, $property)) {
            throw new LogicException;
        }

        $refl = (new AccessProperty)($object, $property);

        if (!$refl->isPublic()) {
            $refl->setAccessible(true);
        }

        $value = $refl->getValue($object);

        if (!$refl->isPublic()) {
            $refl->setAccessible(false);
        }

        return $value;
    }
}
