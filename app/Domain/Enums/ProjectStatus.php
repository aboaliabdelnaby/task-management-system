<?php

namespace App\Domain\Enums;

enum ProjectStatus :string
{
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case ARCHIVED = 'archived';
}
