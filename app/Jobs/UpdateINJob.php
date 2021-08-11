<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Http;

class UpdateRassJob extends Job
{

    /**
     * @var string
     */
    private $inRassBaseUrl;

    /**
     * @var string
     */
    private $psId;

    /**
     * @var array
     */
    private $requestBody;

/**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $id, array $body)
    {
        $this->psId = $id;
        $this->requestBody = $body;
        $this->inRassBaseUrl = config("app.in_rass_url");
    }

    /**
     * Execute the job.
     *
     * @return void
     */
     public function handle()
     {
         $psId = $this->psId;
         $requestBody = $this-> requestBody;
         Http::put($this->inRassBaseUrl.'?nationalId='.$psId, $requestBody);
     }
}
