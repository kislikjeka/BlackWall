<?php


namespace App\Service;


use App\Models\Game;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class GameService
{
    /**
     * Маппинг действий и индексов смещения
     * @var int[]
     */
    const MOVES = [
        'up' => -4,
        'down' => +4,
        'left' => -1,
        'right' => +1
    ];

    /**
     * Создает игру
     *
     * @param User $user
     * @param string|null $game_state
     * @return Game
     */
    public function createGameIfNotExist(User $user, ?string $game_state): Game
    {
        /** @var Game $game */
        $game = $user->notCompletedGames()->first();
        if ($game) {
            return $game;
        }

        $field = $this->createGameField($game_state);
        $game = new Game();
        $game->game_state = $field->implode(',');
        $game->updateTimestamps();
        $game->user()->associate($user);
        $game->save();

        return $game;
    }

    /**
     * Создает игровое поле
     *
     * @param string|null $state
     * @return Collection
     */
    private function createGameField(?string $state = null): Collection
    {
        if ($state) {
            $array_state = explode(',', $state);

            return collect($array_state);
        } else {
            $array_state = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
            $field = collect($array_state);
            do {
                $field = $field->shuffle();
            } while (!$this->checkIfSolving($field));

            $field->add(0);

            return $field;
        }
    }


    /**
     * Проверяет поле на решаемость
     *
     * @param Collection $field
     * @return bool
     */
    private function checkIfSolving(Collection $field): bool
    {
        $inv = 0;
        for ($i = 0; $i < $field->count(); $i++) {
            $first_item = $field[$i];
            for ($j = $i + 1; $j < $field->count(); $j++) {
                $second_item = $field[$j];
                if ($first_item > $second_item) {
                    $inv += 1;
                }
            }
        }

        return $inv % 2 == 0;
    }

    /**
     * Проверяет решение
     *
     * @param Game $game
     * @param array $steps
     * @return bool
     */
    public function solveGame(Game $game, array $steps): bool
    {
        $field = $this->createGameField($game->game_state);
        foreach ($steps as $step) {
            $this->doMove($field, $step['move'], $step['index']);
        }
        if ($this->checkIsSolved($field)){
            $game->is_complited = true;
            $game->completed_at = Carbon::now();
        }

        $game->save();

        return $game->is_complited;
    }

    /**
     * Сделать ход
     *
     * @param $field
     * @param string $move
     * @param int $index
     */
    private function doMove(&$field, string $move, int $index)
    {
        $targetIndex = $index + self::MOVES[$move];
        $target = $field[$targetIndex];
        $field[$targetIndex] = $field[$index];
        $field[$index] = $target;
    }

    /**
     * Проверяет находится ли поле в решенном состоянии
     *
     * @param Collection $field
     * @return bool
     */
    private function checkIsSolved(Collection $field): bool
    {
        return ;
    }
}
