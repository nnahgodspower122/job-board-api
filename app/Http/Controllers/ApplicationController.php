<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;
use App\Http\Requests\ApplyJobRequest;
use App\Jobs\ProcessApplicationFiles;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    use HttpResponses;

    public function apply(ApplyJobRequest $request, Job $job)
    {
        try {
            // Store the files immediately and get their paths
            $coverLetterPath = $request->file('cover_letter')->store('cover_letters', 'public');
            $resumePath = $request->file('resume')->store('resumes', 'public');

            $application = Application::create([
                'candidate_id' => Auth::id(),
                'job_id' => $job->id,
                'cover_letter' => null,
                'resume' => null,
            ]);

            // Dispatch the job
            ProcessApplicationFiles::dispatch($application, $coverLetterPath, $resumePath);

            return $this->successResponse('Application submitted successfully. Your files are being processed.', $application, 201);
        } catch (\Exception $e) {
            \Log::error('Error submitting application: ' . $e->getMessage());

            return $this->errorResponse('Failed to submit application. Please try again later.', null, 500);
        }
    }
}