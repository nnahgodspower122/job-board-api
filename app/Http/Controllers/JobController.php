<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Log; 
use App\Http\Requests\JobRequest;
use Illuminate\Support\Facades\Cache; 

class JobController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        // Generate a unique cache key based on the request parameters
        $cacheKey = 'jobs_' . md5(json_encode($request->all()));

        // Attempt to retrieve cached jobs
        $jobs = Cache::remember($cacheKey, 300, function () use ($request) { // Cache for 5 minutes (300 seconds)
            $query = Job::whereNotNull('published_at');

            if ($request->has('location')) {
                $query->where('location', $request->location);
            }

            if ($request->has('is_remote')) {
                $isRemote = filter_var($request->is_remote, FILTER_VALIDATE_BOOLEAN);
                $query->where('is_remote', $isRemote ? 1 : 0);
            }

            if ($request->has('keyword')) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'LIKE', '%' . $request->keyword . '%')
                        ->orWhere('description', 'LIKE', '%' . $request->keyword . '%');
                });
            }

            return $query->paginate(10); 
        });

        if ($jobs->isEmpty()) {
            return $this->errorResponse('No jobs found', null, 404);
        }

        return $this->successResponse('Jobs retrieved successfully', $jobs, 200);
    }

    public function companyJobs(Request $request)
    {
        $query = Job::query();

        $jobs = $query->paginate(10);    

        if ($jobs->isEmpty()) {
            return $this->errorResponse('No jobs found', null, 404);
        }

        return $this->successResponse('Jobs retrieved successfully', $jobs, 200);
    }

    // Create a Job (Company only)
    public function store(JobRequest $request)
    {
        $job = Auth::user()->jobs()->create($request->all());

        return $this->successResponse('Job created successfully', $job, 201);
    }

    public function update(JobRequest $request, Job $job)
    {
    
        if (Auth::id() !== $job->company_id) {
            return $this->errorResponse('Unauthorized: You do not own this job.', null, 403);
        }

        $job->update($request->all());

        return $this->successResponse('Job updated successfully', $job, 200);
    }

    public function destroy(Job $job)
    {
        if (Auth::id() !== $job->company_id) {
            return $this->errorResponse('Unauthorized: You do not own this job.', null, 403);
        }

        $job->delete();

        return $this->successResponse('Job deleted successfully', null, 200);
    }
}