<?php

declare(strict_types = 1);

namespace App\Service;

class SecondAdditionalService
{
    /**
     * @return string
     */
    public function echo(): string
    {
        return FirstAdditionalService::REAL_VALUE;
    }
}
