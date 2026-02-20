<?php

declare(strict_types=1);

namespace Modules\Projects\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Projects\Database\Factories\TaskFactory;
use Modules\Projects\Enums\TaskPriority;
use Modules\Projects\Enums\TaskStatus;

/**
 * @mixin IdeHelperTask
 */
class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'completed_at',
        'description',
        'due_at',
        'estimated_hours',
        'is_pinned',
        'priority',
        'project_id',
        'status',
        'title',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'due_at' => 'datetime',
            'estimated_hours' => 'decimal:2',
            'is_pinned' => 'boolean',
            'priority' => TaskPriority::class,
            'status' => TaskStatus::class,
        ];
    }

    protected static function newFactory(): TaskFactory
    {
        return TaskFactory::new();
    }

    /** @return BelongsTo<Project, $this> */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
