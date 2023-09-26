<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends Controller
{
    /**
     * Display a listing of this resource.
     *
     *@param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        if ($user->role == User::$ROLE_DRIVER) {
            throw new ModelNotFoundException();
        }

        $this->authorize('index', [Service::class, $user]);

        $services = Service::where('user_id', $user->id)
            ->with(['user'])
            ->orderBy('updated_at', 'DESC')
            ->paginate(5);

        return ServiceResource::collection($services);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\CreateServiceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateServiceRequest $request)
    {
        Service::create([
            'name' => $request->name,
            'cost' => $request->cost,
            'description' => $request->description,
            'user_id' => auth()->id()
        ]);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
