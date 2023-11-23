<?php

namespace App\Http\Resources;

use App\Services\RequestService;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestResource extends JsonResource
{
    public function __construct(
        $resource,
        public RequestService $requestService,
    ) {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(HttpRequest $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->resource->author->name,
            'email' => $this->resource->author->email,
            'message' => $this->massage,
            'status' => $this->requestService
                ->getHumanIntelligibleStatus($this->resource),
            'answer' => $this->answer,
            'answer_at' => $this->answer_at,
            'created_at' => $this->created_at,
        ];
    }
}
