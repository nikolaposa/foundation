<?php
/**
 * This file is part of the Phoundation package.
 *
 * Copyright (c) Nikola Posa
 *
 * For full copyright and license information, please refer to the LICENSE file,
 * located at the package root folder.
 */

declare(strict_types=1);

namespace Phoundation\Di\Container\Factory;

use Phoundation\Config\Config;

/**
 * @author Nikola Posa <posa.nikola@gmail.com>
 */
abstract class AbstractFactory implements FactoryInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var string
     */
    private $diConfigKey;

    /**
     * @var string
     */
    private $configServiceName;

    public function __invoke(Config $config, string $diConfigKey = self::DEFAULT_DI_CONFIG_KEY, string $configServiceName = self::DEFAULT_CONFIG_SERVICE_NAME)
    {
        $this->config = $config;
        $this->diConfigKey = $diConfigKey;
        $this->configServiceName = $configServiceName;

        $container = $this->createContainer();

        $this->configure($container);

        return $container;
    }

    abstract protected function createContainer();

    abstract protected function configure($container);

    final protected function getConfig()
    {
        return $this->config;
    }

    final protected function getConfigServiceName()
    {
        return $this->configServiceName;
    }

    final protected function getDiConfig() : array
    {
        if (empty($this->config[$this->diConfigKey]) || !is_array($this->config[$this->diConfigKey])) {
            return [];
        }

        return $this->config[$this->diConfigKey];
    }

    final protected function getDiConfigGroup(string $group) : array
    {
        $diConfig = $this->getDiConfig();

        if (empty($diConfig[$group]) || !is_array($diConfig[$group])) {
            return [];
        }

        return $diConfig[$group];
    }
}
