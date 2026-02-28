<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function store(StoreProjectRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Project created successfully',
            'data' => $request->validated(),
        ], 201);
    }
}
