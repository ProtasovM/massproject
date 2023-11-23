<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexRequestRequest;
use App\Http\Requests\StoreRequestRequest;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use App\Models\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Request::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequestRequest $request): Response|JsonResponse //todo добавить курсор в пагинатор
    {
        $builder = Request::query();

        if (Auth::user()->hasRole(Role::MODERATOR_TYPE) === false) {
            $builder->where('author_id', '=', Auth::user()->id);
        }

        $paginator = $builder->paginate(
            $request->per_page ?? 100,
            page: $request->page ?? 1,
        );

        if ($paginator->total() === 0) {
            return response(null, Response::HTTP_NO_CONTENT);
        }

        return response()->json([
            'items' => $paginator->items(),
            'page' => $paginator->currentPage(),
            'total' => $paginator->total(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequestRequest $request): JsonResponse
    {
        return \response()->json(
            Auth::user()->requests()->create($request->validated()),
            Response::HTTP_CREATED,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): JsonResponse
    {
        return \response()->json($request);
    }
}
