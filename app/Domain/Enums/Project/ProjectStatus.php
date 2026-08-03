<?php

namespace App\Domain\Enums\Project;

enum ProjectStatus :string
{
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case ARCHIVED = 'archived';
}
