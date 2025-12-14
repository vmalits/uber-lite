<?php

declare(strict_types=1);

namespace App\Enums;

enum ProfileStep: string
{
    case PHONE_VERIFIED = 'phone_verified';

    case EMAIL_VERIFIED = 'email_verified';

    case COMPLETED = 'completed';
}
