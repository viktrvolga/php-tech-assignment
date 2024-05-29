<?php

declare(strict_types=1);

namespace App\Enums;

enum OutputTypeEnum: string
{
    case CSV = 'csv';
    case TXT = 'txt';
}
