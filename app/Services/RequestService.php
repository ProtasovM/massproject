<?php

namespace App\Services;

use App\Exceptions\Requests\RequestAlreadyAnswered;
use App\Models\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RequestService
{
    public const TOTAL_CACHE_KEY = 'request_total_rows';

    public function getHumanIntelligibleStatus(Request $request): string
    {
        return Request::STATUS_HUMAN_NAMES[$request->status];
    }

    public function answerRequest(
        User $respondent,
        Request $request,
        string $answer,
    ): void {
        DB::beginTransaction();

        $request->refresh();

        if ($request->answer !== null) {
            DB::rollBack();
            throw new RequestAlreadyAnswered();
        }

        $request->answer = $answer;
        $request->respondent_id = $respondent->id;
        $request->answered_at = Carbon::now();
        $request->save();

        DB::commit();
    }

    public function getTotalRows(): int
    {
        return Cache::get(
            static::TOTAL_CACHE_KEY,
            function () {
                return DB::table((new Request)->getTable())->count();
            }
        );
    }

    public function incrementTotalRows(): void
    {
        Cache::increment(static::TOTAL_CACHE_KEY);
    }
}
