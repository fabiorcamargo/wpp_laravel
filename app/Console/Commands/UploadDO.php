<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UploadDO extends Command
{
    public $timeout = 10200;
    protected $signature = 'upload:s3 {path}';
    protected $description = 'Upload a file to S3 using s3cmd';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $path = $this->argument('path');
        $s3cmdCommand = "s3cmd put $path s3://profissionaliza-space";
        exec($s3cmdCommand, $output, $returnCode);

        if ($returnCode === 0) {
            $this->info('File uploaded to S3 successfully.');
        } else {
            $this->error('An error occurred while uploading the file to S3.');
        }
    }
}
