<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobRequest;
use App\Http\Resources\JobResource;
use App\Models\Job;
use App\Models\User;
use App\Models\Bid;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *@param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = Job::with([
                        'user',
                        'category',
                    ])
                    ->where('jobs.user_id', auth()->id())
                    ->orderBy('created_at', 'DESC')
                    ->paginate(5);

        foreach ($jobs as $index => $job) {
            if(!empty($job->fulfilledJob)) {
                $bid = Bid::find($job->fulfilledJob->bid_id);
                $job->bider = $bid->user;
                $job->bid = $bid;
                $job->fulfilled = (bool)$job->fulfilledJob->fulfilled;
            }
            if ($job->fulfilled == true) {
                $jobs->forget($index);
            }
        }

        return JobResource::collection($jobs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreJobRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreJobRequest $request)
    {
        Job::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'vehicle' => $request->vehicle,
            'user_id' => auth()->id()
        ]);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        // if ($job->fulfilledJob()->exists()) {
        //     throw new ModelNotFoundException();
        // }

        $this->authorize('show', [Job::class, $job]);

        if(!empty($job->fulfilledJob)) {
            $bid = Bid::find($job->fulfilledJob->bid_id);
            $job->bider = $bid->user;
            $job->bid = $bid;
            $job->fulfilled = (bool)$job->fulfilledJob->fulfilled;
        }

        return new JobResource($job->load(['user', 'category']));
    }
}
