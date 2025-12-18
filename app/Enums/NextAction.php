<?php

declare(strict_types=1);

namespace App\Enums;

enum NextAction: string
{
    case SELECT_ROLE = 'select_role';
    case ADD_EMAIL = 'add_email';
    case VERIFY_EMAIL = 'verify_email';
    case COMPLETE_PROFILE = 'complete_profile';
    case DONE = 'done';
}
