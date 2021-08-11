<?php

namespace App\Jobs;

use DateTime;
use Illuminate\Support\Facades\Http;
use Throwable;

class UpdateINJob extends Job
{

    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $requestBody;

/**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $url, array $body)
    {
        $this->url = $url;
        $this->requestBody = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
     public function handle()
     {
         $url = $this->url;
         $requestBody = $this-> requestBody;
         Http::put($url, $requestBody);
     }

    /**
     * @return DateTime
     */
     public function retryUntil(): DateTime
     {
         return now()->addHours(24);
     }
}
