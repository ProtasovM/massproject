<?php

namespace App\Http\Controllers;

use App\Exceptions\Requests\RequestAlreadyAnswered;
use App\Http\Requests\RequestAnswerRequest;
use App\Services\RequestService;
use Illuminate\Support\Facades\Auth;
use \App\Models\Request;

/**
 * Бизнес процесс как ресурс
 *
 * Ответ от админа на заявку
 */
class RequestAnswerController extends Controller
{
    public function __construct(
        public RequestService $requestService,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(RequestAnswerRequest $request, Request $requestModel)
    {
        try {
            $this->requestService->answerRequest(
                Auth::user(),
                $requestModel,
                $request->validated()['answer'],
            );
        } catch (RequestAlreadyAnswered) {
            return response()->json(
                [
                    'message' => __('This request already answered.')
                ],
                400
            );
        }

        return \App\Http\Resources\Request::make($requestModel);
    }
}
