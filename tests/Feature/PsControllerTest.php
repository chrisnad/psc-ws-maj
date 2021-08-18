<?php

namespace Tests\Feature;

use App\Http\Controllers\Ui\PsController;
use App\Jobs\UpdateINJob;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PsControllerTest extends TestCase
{
    use DispatchesJobs;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPutResponse()
    {
        $requestBody = array();
        $requestBody['phone'] = '174';
        $requestBody['email'] = 'toto@to.fr';

        Queue::fake();
        Http::fake();

        $psController = new PsController();
        Queue::assertNothingPushed();
        $psController->putResponse('190000042/021721', $requestBody);
        Http::assertSentCount(1);
        Queue::assertPushed(UpdateINJob::class);
    }

    public function testUpdateINJob()
    {
        $requestBody = array();
        $requestBody['phone'] = '174';
        $requestBody['email'] = 'toto@to.fr';

        Http::fake();
        $updateJob = new UpdateINJob('/api/ps/123456%2F7891-REF/professions/abc456', $requestBody);
        $updateJob->handle();
        Http::assertSentCount(1);
    }

    public function testDispatch()
    {
        $requestBody = array();
        $requestBody['phone'] = '174';
        $requestBody['email'] = 'toto@to.fr';
        dispatch(new UpdateINJob('http://localhost:9000/api/ps/12345678', $requestBody));
        // TODO : mock GET
    }
}
