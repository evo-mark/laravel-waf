<?php

declare(strict_types=1);

namespace EvoMark\LaravelWaf\Rules;

use EvoMark\LaravelWaf\Models\WafRule;

abstract class BaseRule
{
    protected static string $name;

    protected int $order;

    public function getName(): string
    {
        if (empty(self::$name)) {
            throw new \Exception("WAF rule hasn't declared a name");
        }

        return self::$name;
    }

    public function getOrder(): int
    {
        if (empty($this->order)) {
            throw new \Exception("WAF rule hasn't declared an order");
        }

        return $this->order;
    }

    /**
     * Callback for when the WAF Rule model is updated in the database
     */
    public function onConfigUpdated(array $config): void
    {
        // Intentionally left blank
    }
}
