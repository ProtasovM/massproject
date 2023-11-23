<?php

namespace App\Services;

use App\Models\Request;

class RequestService
{
    public function getHumanIntelligibleStatus(Request $request): string
    {
        return Request::STATUS_HUMAN_NAMES[$request->status];
    }
}
