<?php

namespace App\Http\Controllers;

use App\Models\FulfilledJob;
use Symfony\Component\HttpFoundation\Response;

class FulfillJobController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Models\FulfilledJob $job
     * @return \Illuminate\Http\Response
     */
    public function store(FulfilledJob $job)
    {
        $this->authorize('fulfill', [FulfilledJob::class, $job]);

        $fulfilled = FulfilledJob::findOrFail($job->id);

        $fulfilled->fulfilled = true;
        $fulfilled->save();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
