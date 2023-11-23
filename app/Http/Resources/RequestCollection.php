<?php

namespace App\Http\Resources;

use App\Services\RequestService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\App;

class RequestCollection extends ResourceCollection
{
    public static $wrap = null;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'items' => $this->collection,
            'previous' => $this->resource->previousPageUrl(),
            'next' => $this->resource->nextPageUrl(),
            'total' => App::make(RequestService::class)->getTotalRows(),
        ];
    }
}
