<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Описание сущности пользователь
 *
 * Class User
 * @package App\Models
 *
 * @property int $id
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Связь со всеми играми
     *
     * @return HasMany
     */
    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    /**
     * Связь со всеми незаконченными играми
     *
     * @return HasMany
     */
    public function notCompletedGames(): HasMany
    {
        return $this->games()->where('is_completed', 0);
    }
}
