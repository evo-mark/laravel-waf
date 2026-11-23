<?php

declare(strict_types=1);

namespace EvoMark\LaravelWaf\Rules;

use EvoMark\LaravelWaf\Contracts\RuleContract;

class BlockDangerousFiles extends BaseRule implements RuleContract
{
    public function apply(): void
    {
        throw new \Exception('Not implemented');
    }
}
