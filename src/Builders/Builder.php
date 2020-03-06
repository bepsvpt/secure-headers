<?php

namespace Bepsvpt\SecureHeaders\Builders;

abstract class Builder
{
    /**
     * Builder config.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Builder constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Get result.
     *
     * @return string
     */
    abstract public function get(): string;
}
