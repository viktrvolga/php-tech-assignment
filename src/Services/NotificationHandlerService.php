<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OutputTypeEnum;

class NotificationHandlerService
{
    public function __construct(
        private OutputTypeEnum $outputType
    ) {
    }

    public function handle(): void
    {
    }
}
