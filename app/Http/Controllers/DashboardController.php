<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function companyDashboard()
    {
        try {
            $company = Auth::user();

            $jobCount = Job::where('company_id', $company->id)->count();

            $totalApplications = Application::whereIn(
                'job_id',
                Job::where('company_id', $company->id)->pluck('id')
            )->count();

            return response()->json([
                'success' => true,
                'message' => 'Company dashboard data retrieved successfully.',
                'data' => [
                    'job_posts' => $jobCount,
                    'total_applications' => $totalApplications,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching company dashboard data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch company dashboard data.',
            ], 500);
        }
    }

    public function candidateDashboard()
    {
        try {
            $candidate = Auth::user();

            $appliedJobs = Application::where('candidate_id', $candidate->id)
                ->with('job')
                ->get()
                ->map(function ($application) {
                    return [
                        'job_title' => $application->job->title,
                        'job_description' => $application->job->description,
                        'applied_at' => $application->created_at->toDateTimeString(),
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Candidate dashboard data retrieved successfully.',
                'data' => $appliedJobs,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching candidate dashboard data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch candidate dashboard data.',
            ], 500);
        }
    }
}