<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'title', 'description', 'status', 'priority', 'due_date', 'position'];

    protected function casts(): array
    {
        return [
            'status' => TaskStatus::class,
            'priority' => TaskPriority::class,
            'due_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /** هل تجاوزت المهمة تاريخ التسليم ولم تُنجز؟ */
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && ! $this->due_date->isToday()
            && $this->status !== TaskStatus::Done;
    }

    // ---------- Scopes للفلترة ----------

    public function scopeOverdue(Builder $query): void
    {
        $query->whereDate('due_date', '<', today())->where('status', '!=', TaskStatus::Done);
    }

    /** يطبّق فلاتر البحث القادمة من الرابط (?search=&status=&priority=&project=) */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query
            ->when($filters['search'] ?? null, fn ($q, $s) => $q->where(fn ($q) => $q
                ->where('title', 'like', "%{$s}%")->orWhere('description', 'like', "%{$s}%")))
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($filters['priority'] ?? null, fn ($q, $v) => $q->where('priority', $v))
            ->when($filters['project'] ?? null, fn ($q, $v) => $q->where('project_id', $v));
    }
}
