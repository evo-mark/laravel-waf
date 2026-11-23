<?php

declare(strict_types=1);

namespace EvoMark\LaravelWaf\Contracts;

interface RuleContract
{
    public function apply(): void;

    public function onConfigUpdated(array $config): void
}
