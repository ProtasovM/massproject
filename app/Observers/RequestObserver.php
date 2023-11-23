<?php

namespace App\Observers;

use App\Events\RequestAnsweredEvent;
use App\Models\Request;

class RequestObserver
{
    /**
     * Eloquent не запоминиает старые значения ПОСЛЕ сохранения
     * а мы не хотим чтото делать ДО
     *
     * @var array
     */
    private array $localCache;

    public function creating(Request $request): void
    {
        /*
         * Выставим начальный статус
         */
        $request->status = Request::ACTIVE_STATUS;
    }

    public function updating(Request $request): void
    {
        if ($request->isDirty(['answer'])) {
            $this->localCache[$request->id] = $request->getOriginal();
        }
    }

    public function updated(Request $request): void
    {
        /*
         * Если ответили, создадим евент
         */
        if (
            $request->wasChanged(['answer'])
            && isset($this->localCache[$request->id])
            && $this->localCache[$request->id]['answer'] === null
        ) {
            event(new RequestAnsweredEvent($request));

            unset($this->localCache[$request->id]);
        }
    }
}
