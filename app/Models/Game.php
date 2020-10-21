<?php


namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * Class Game
 * @package App\Models
 *
 * @property string $game_state
 * @property int $user_id
 * @property boolean $is_completed
 *
 * @property Carbon $created_at
 * @property Carbon $completed_at
 *
 * @method static Builder notCompletedByUserId(int $userId)
 */
class Game extends Model
{
    protected $casts = [
        'is_completed' => 'bool',
        'created_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected $table = 'game';

    /**
     * Описывает связь игры с пользователем
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получение игры по пользователю
     *
     * @param Builder $query
     * @param int $userId
     * @return Builder
     */
    public function scopeNotCompletedByUserId(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId)->where('is_completed', 0);
    }
}
