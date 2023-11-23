<?php

namespace App\Services;

use App\Exceptions\Requests\RequestAlreadyAnswered;
use App\Models\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RequestService
{
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
}
