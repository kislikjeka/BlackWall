<?php


namespace App\Http\Controllers;


use App\Models\Game;
use App\Models\User;
use App\Service\GameService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    /** @var GameService */
    private $gameService;

    /**
     * GameController constructor.
     * @param GameService $gameService
     */
    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    /**
     * Создает игру и присылает поле
     * @param Request $request
     * @return JsonResponse
     */
    public function createGame(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $game = $this->gameService->createGameIfNotExist($user, $request->state);
        return response()->json(['field' => $game->game_state, 'game_id' => $game->getKey()]);
    }

    /**
     * Проверяет решение
     * @param $gameId
     * @return JsonResponse
     */
    public function solveGame(Request $request, $gameId): JsonResponse
    {
        $game = Game::find($gameId);
        return response()->json(['is_win' => $this->gameService->solveGame($game, $request->steps)]);
    }
}
