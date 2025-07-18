<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class CreateTextFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  
    protected $outputFilePath;
    
    public function __construct(string $outputFilePath)
    {
        $this->outputFilePath = $outputFilePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
           // ตรรกะทั้งหมดในการรัน Node.js ถูกย้ายมาไว้ที่นี่
        $command = 'HOME=/tmp ' .
                   escapeshellarg('/usr/bin/node') .
                   ' --max-old-space-size=2048 ' .
                   escapeshellarg(base_path('nodejs_create_textfile.js')) . ' ' .
                   escapeshellarg($this->outputFilePath);

        $process = Process::fromShellCommandline($command);
        $process->setTimeout(60);
        $process->run();

        // หากเกิดข้อผิดพลาด ให้บันทึกลงใน log ของ Laravel
        if (!$process->isSuccessful()) {
            Log::error('CreateTextFileJob failed: ' . $process->getErrorOutput());
        }
    }
}
