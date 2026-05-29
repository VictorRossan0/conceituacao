<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Represents an authenticated system user.
 *
 * Users can authenticate through Laravel Sanctum, can be soft deleted,
 * and may be associated with multiple profiles through a many-to-many
 * relationship.
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * Attributes that can be mass assigned.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Attributes hidden from array and JSON serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Defines attribute casting rules.
     *
     * The password field is automatically hashed by Laravel when assigned.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Defines the many-to-many relationship between users and profiles.
     *
     * A user can have multiple profiles, and the pivot table also stores
     * timestamps for when each association was created or updated.
     */
    public function profiles(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class)->withTimestamps();
    }

    /**
     * Checks whether the user has the Administrador profile.
     *
     * This method is used by the authorization middleware to restrict
     * profile management and user-profile association features.
     */
    public function isAdministrator(): bool
    {
        return $this->profiles()
            ->where('name', 'Administrador')
            ->exists();
    }
}
