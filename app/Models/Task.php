<?php

namespace App\Models;

use App\Domain\Enums\Task\TaskPriority;
use App\Domain\Enums\Task\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes,HasFactory;

    protected $fillable = [
        'project_id','title','description','priority','status','due_date'
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,
        'due_date' => 'datetime'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }}
