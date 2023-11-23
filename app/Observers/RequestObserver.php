<?php

namespace App\Observers;

use App\Events\RequestAnsweredEvent;
use App\Models\Request;
use App\Services\RequestService;

class RequestObserver
{
    /**
     * Eloquent не запоминиает старые значения ПОСЛЕ сохранения
     * а мы не хотим чтото делать ДО
     *
     * @var array
     */
    private array $localCache;

    public function __construct(
        public RequestService $requestService,
    ) {
    }

    public function creating(Request $request): void
    {
        /*
         * Выставим начальный статус
         */
        $request->status = Request::ACTIVE_STATUS;
    }

    public function created(Request $request): void
    {
        /*
         * Обновим тотал
         */
        $this->requestService->incrementTotalRows();
    }

    public function updating(Request $request): void
    {
        if ($request->isDirty(['answer', 'status'])) {
            $this->localCache[$request->id] = $request->getOriginal();
        }
    }

    public function updated(Request $request): void
    {
        /*
         * Если ответили, создадим евент
         */
        if (
            $request->wasChanged(['answer', 'status'])
            && isset($this->localCache[$request->id])
            && $this->localCache[$request->id]['answer'] === null
            && !empty($request->answer)
            && $this->localCache[$request->id]['status'] === Request::ACTIVE_STATUS
            && $request->status === Request::RESOLVED_STATUS
        ) {
            event(new RequestAnsweredEvent($request));

            unset($this->localCache[$request->id]);
        }
    }
}
