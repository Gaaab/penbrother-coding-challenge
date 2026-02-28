<?php

namespace App\Enums;

enum AmortizationState: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case PAID = 'paid';
}
