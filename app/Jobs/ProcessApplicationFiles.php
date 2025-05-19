<?php

namespace App\Jobs;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessApplicationFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $application;
    protected $coverLetterPath;
    protected $resumePath;

    /**
     * Create a new job instance.
     */
    public function __construct(Application $application, $coverLetterPath, $resumePath)
    {
        $this->application = $application;
        $this->coverLetterPath = $coverLetterPath;
        $this->resumePath = $resumePath;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Update the application record with the file URLs
        $this->application->update([
            'cover_letter' => url(Storage::url($this->coverLetterPath)),
            'resume' => url(Storage::url($this->resumePath)),
        ]);
    }
}