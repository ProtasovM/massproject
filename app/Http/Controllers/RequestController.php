<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexRequestRequest;
use App\Http\Requests\StoreRequestRequest;
use App\Http\Resources\RequestCollection;
use App\Http\Resources\Request as RequestResource;
use App\Models\Role;
use App\Services\RequestFilterService;
use App\Services\RequestService;
use Illuminate\Http\JsonResponse;
use App\Models\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function __construct(
        public RequestService $requestService,
        public RequestFilterService $requestFilterService,
    )
    {
        $this->authorizeResource(Request::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/requiests/",
     *     description="Paginatable list of requests",
     *     @OA\Response(response="200", description="Display a listing of the requests"),
     *     @OA\Response(response="204", description="No content"),
     *     @OA\Response(response="401", description="Not authorized."),
     *     @OA\Response(response="422", description="Validation error."),
     *  )
     */
    public function index(IndexRequestRequest $request)
    {
        $builder = Request::query();

        if (Auth::user()->hasRole(Role::MODERATOR_TYPE) === false) {
            $builder->where('author_id', '=', Auth::user()->id);
        }

        $this->requestFilterService->resolveFilterFromRequest($request, $builder);

        $paginator = $builder->cursorPaginate(
            $request->per_page ?? 100,
        );

        if ($this->requestService->getTotalRows() === 0) {
            return response(null, Response::HTTP_NO_CONTENT);
        }

        return RequestCollection::make($paginator);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/requiests/{id}",
     *     description="Store a newly created requiest in storage.",
     *     @OA\Response(response="201", description="New request was created."),
     *     @OA\Response(response="401", description="Not authorized."),
     *     @OA\Response(response="403", description="Forbidden."),
     *     @OA\Response(response="422", description="Validation error."),
     * )
     */
    public function store(StoreRequestRequest $request)
    {
        return RequestResource::make(
            Auth::user()->requests()
                ->create($request->validated())
        )->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/api/requiests/{id}",
     *     description="Display the specified requies.",
     *     @OA\Response(response="200", description="Display the specified request."),
     *     @OA\Response(response="401", description="Not authorized."),
     *     @OA\Response(response="403", description="Forbidden."),
     *     @OA\Response(response="404", description="Not found."),
     *  )
     */
    public function show(Request $request)
    {
        return RequestResource::make($request);
    }
}
