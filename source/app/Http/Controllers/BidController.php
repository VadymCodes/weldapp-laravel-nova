<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBidRequest;
use App\Http\Resources\BidResource;
use App\Models\Bid;
use App\Models\Job;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *@param \App\Models\Job $job
     * @return \Illuminate\Http\Response
     */
    public function index(Job $job)
    {
        $this->authorize('show', [Job::class, $job]);

        $bids = Bid::where('job_id', $job->id)
            ->with(['user', 'fulfilledJob'])
            ->latest()
            ->paginate(5);

        return BidResource::collection($bids);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Resources\BidResource  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBidRequest $request, Job $job)
    {
        if (!Job::find($job->id)->exists()) {
            throw new ModelNotFoundException();
        }

        $newBid = Bid::create([
            'comment' => $request->comment,
            'cost' => $request->cost,
            'job_id' => $job->id,
            'user_id' => auth()->id()
        ]);

        return new BidResource($newBid->load(['user']));
    }
}
