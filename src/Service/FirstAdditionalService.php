<?php

declare(strict_types=1);

namespace App\Service;

class FirstAdditionalService
{
    public const REAL_VALUE = 'real value';

    public const FAKE_VALUE = 'fake value';

    /**
     * @return string
     */
    public function echo(): string
    {
        return self::REAL_VALUE;
    }
}
