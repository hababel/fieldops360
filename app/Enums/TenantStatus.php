<?php

declare(strict_types=1);

namespace App\Enums;

enum TenantStatus: string
{
    case PENDING_PAYMENT = 'PENDING_PAYMENT';
    case ACTIVE = 'ACTIVE';
    case SUSPENDED = 'SUSPENDED';
    case CANCELLED = 'CANCELLED';
}
