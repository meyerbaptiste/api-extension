<?php

/*
 * This file is part of the ApiExtension package.
 *
 * (c) Vincent Chalamon <vincent@les-tilleuls.coop>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ApiExtension\Populator\Guesser;

/**
 * @author Vincent Chalamon <vincent@les-tilleuls.coop>
 */
class StringGuesser extends AbstractGuesser
{
    public function supports(array $mapping): bool
    {
        return in_array($mapping['type'], ['string', 'text']);
    }

    public function getValue(array $mapping)
    {
        try {
            return $this->faker->format($mapping['fieldName']);
        } catch (\InvalidArgumentException $exception) {
            if ('text' === $mapping['type']) {
                return $this->faker->paragraph();
            }

            return $this->faker->text($mapping['length'] ?? 200);
        }
    }
}
