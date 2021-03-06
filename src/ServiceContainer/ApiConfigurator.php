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

namespace ApiExtension\ServiceContainer;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Vincent Chalamon <vincent@les-tilleuls.coop>
 */
final class ApiConfigurator
{
    /**
     * @var ContainerInterface
     */
    private $container;
    private $parameters;

    public function __construct(KernelInterface $kernel, array $parameters)
    {
        $this->container = $kernel->getContainer();
        $this->parameters = $parameters;
    }

    public function configure($service)
    {
        foreach ($this->parameters as $name => $value) {
            $methodName = 'set'.ucfirst($name);
            if (!method_exists($service, $methodName)) {
                continue;
            }

            if (preg_match('/^%(.*)%$/', $value, $matches)) {
                call_user_func([$service, $methodName], $this->container->getParameter($matches[1]));
            } else {
                call_user_func([$service, $methodName], $this->container->get('@' === substr($value, 0, 1) ? substr($value, 1) : $value));
            }
        }
    }
}
