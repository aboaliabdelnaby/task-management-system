<?php

namespace App\Models;

use App\Domain\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name','description','status','user_id'];

    protected $casts = [
        'status' => ProjectStatus::class,
    ];
    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->user_id = auth()->id();
            }
        });
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOwned($q)
    {
        return $q->where('user_id', auth()->id());
    }
}
