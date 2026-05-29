<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Represents an access profile assigned to users.
 *
 * Profiles define authorization roles within the system. They are related
 * to users through a many-to-many relationship and use soft deletes to
 * preserve historical records.
 */
class Profile extends Model
{
    use SoftDeletes;

    /**
     * Attributes that can be mass assigned.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Defines the many-to-many relationship between profiles and users.
     *
     * A profile can be assigned to multiple users, and each association
     * is stored in the profile_user pivot table.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
