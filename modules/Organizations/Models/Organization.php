<?php

declare(strict_types=1);

namespace Modules\Organizations\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Organizations\Database\Factories\OrganizationFactory;
use Modules\Projects\Models\Project;
use Modules\Users\Models\User;

/**
 * @mixin IdeHelperOrganization
 */
class Organization extends Model
{
    /** @use HasFactory<OrganizationFactory> */
    use HasFactory,
        SoftDeletes;

    /** @var list<string> */
    protected $fillable = [
        'is_active',
        'name',
        'slug',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function newFactory(): OrganizationFactory
    {
        return OrganizationFactory::new();
    }

    /** @return HasMany<Project, $this> */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /** @return BelongsToMany<User, $this> */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }
}
