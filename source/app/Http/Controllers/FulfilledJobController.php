<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest;
use App\Http\Requests\StoreFulfilledJobRequest;
use App\Http\Resources\FulfilledJobResource;
use App\Models\FulfilledJob;
use App\Models\Job;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FulfilledJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *@param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jobs = FulfilledJob::with(
            [
                'job',
                'job.user',
                'job.category',
                'bid',
                'bid.user',
            ]
        )
        ->where('fulfilled', '=', ($request->query('fulfilled') === 'true'))
        ->where(function (Builder $query) {
            $query->whereHas('job.user', function (Builder $query) {
                $query->where('id', auth()->id());
            })
            ->orWhereHas('bid.user', function (Builder $query) {
                $query->where('id', auth()->id());
            });
        })
        ->orderBy('created_at', 'DESC')
        ->paginate(5);

        return FulfilledJobResource::collection($jobs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFulfilledJobRequest  $request
     * @param \App\Models\Job $job
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFulfilledJobRequest $request, Job $job)
    {
        $this->authorize('store', [FulfilledJob::class, $job]);

        $fulfilledJob = FulfilledJob::create([
            'job_id' => $job->id,
            'bid_id' => $request->bid_id,
        ]);

        $job->unsearchable();

        return response()->json(['id' => $fulfilledJob->id], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FulfilledJob $fulfilledJob
     * @return \Illuminate\Http\Response
     */
    public function show(FulfilledJob $fulfilledJob)
    {
        $this->authorize('show', [FulfilledJob::class, $fulfilledJob]);

        return new FulfilledJobResource($fulfilledJob->load([
            'job',
            'job.user',
            'job.category',
            'bid',
            'bid.user',
        ]));
    }

    /**
     * Get fulfilled jobs by id
     *
     *@param string $id
     * @return \Illuminate\Http\Response
     */
    public function getFulfilled(string $id)
    {
        $jobs = FulfilledJob::with(
            [
                'job',
                'job.user',
                'job.category',
                'bid',
                'bid.user',
            ]
        )
        ->where('fulfilled', true)
        ->where(function (Builder $query) use ($id) {
            $query->whereHas('job.user', function (Builder $query) use ($id) {
                $query->where('id', $id);
            })
            ->orWhereHas('bid.user', function (Builder $query) use ($id) {
                $query->where('id', $id);
            });
        })
        ->orderBy('updated_at', 'DESC')
        ->paginate(5);

        return FulfilledJobResource::collection($jobs);
    }

    /**
     * Get fulfilled jobs by id
     *
     *@param string $id
     * @return \Illuminate\Http\Response
     */
    public function getFulfilledForRating(string $id)
    {
        $jobs = FulfilledJob::with(
            [
                'job',
                'job.user',
                'job.category',
                'bid',
                'bid.user',
            ]
        )
        ->where('fulfilled', true)
        ->where(function (Builder $query) use ($id) {
            $query->whereHas('job.user', function (Builder $query) use ($id) {
                $query->where('id', $id);
            })
            ->orWhereHas('bid.user', function (Builder $query) use ($id) {
                $query->where('id', $id);
            });
        })->get();

        return FulfilledJobResource::collection($jobs);
    }

    /**
     * Give a feedback to a driver.
     *
     * @param  \App\Http\Requests\StoreFulfilledJobRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function feedbackByMechanic(FeedbackRequest $request)
    {
        $fulfilled = FulfilledJob::where('job_id', $request->job)->firstOrFail();

        $fulfilled->driver_rating = $request->rating;
        $fulfilled->driver_comment = $request->comment;
        $fulfilled->save();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Give a feedback to a mechanic.
     *
     * @param  \App\Http\Requests\StoreFulfilledJobRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function feedbackByDriver(FeedbackRequest $request)
    {
        $fulfilled = FulfilledJob::where('job_id', $request->job)->firstOrFail();

        $fulfilled->mechanic_rating = $request->rating;
        $fulfilled->mechanic_comment = $request->comment;
        $fulfilled->save();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
