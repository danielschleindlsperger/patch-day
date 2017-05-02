<?php

namespace Tests\Feature;

use App\Company;
use App\PatchDay;
use App\Project;
use App\User;
use App\Protocol;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProtocolClientTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $client;
    protected $company;
    protected $project;
    protected $patchDay;

    public function setUp()
    {
        parent::setUp();

        // Auth
        $this->client = factory(User::class)->create();
        $this->actingAs($this->client);

        $this->company = factory(Company::class)->create();
        $this->project = factory(Project::class)->create();
        $this->patchDay = factory(PatchDay::class)->create();

        $this->patchDay->project()->associate($this->project);
        $this->patchDay->save();

        $this->project->company()->associate($this->company);
        $this->project->save();

        $this->client->company()->associate($this->company);
        $this->client->save();
    }

    /** @test */
    public function client_can_see_a_protocol()
    {
        $protocol = factory(Protocol::class)->create([
            'comment' => 'It was good.',
            'done' => true,
            'due_date' => Carbon::now()->toDateTimeString()
        ]);

        // not associated with client, so should fail
        $response = $this->json('GET', '/protocols/' . $protocol->id);
        $response->assertStatus(403);

        $protocol->patchDay()->associate($this->patchDay);
        $protocol->save();

        $response = $this->json('GET', '/protocols/' . $protocol->id);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'comment' => 'It was good.',
                'done' => true,
            ]);
    }

    /** @test */
    public function client_cannot_create_a_protocol()
    {
        $response = $this->json('POST', '/protocols', [
            'comment' => 'It was good.',
            'done' => true,
            'due_date' => (new Carbon('now + 21 days'))->toDateTimeString()
        ]);
        $response->assertStatus(403);
    }

    /** @test */
    public function client_cannot_edit_a_protocol()
    {
        $protocol = factory(Protocol::class)->create([
            'due_date' => Carbon::now()->toDateTimeString(),
            'comment' => null,
            'done' => false,
        ]);

        $protocol->patchDay()->associate($this->patchDay);
        $protocol->save();

        $this->assertFalse($protocol->done);
        $this->assertNull($protocol->comment);

        $response = $this->json('PUT', '/protocols/'.$protocol->id, [
            'comment' => '<p>It was good.</p>',
            'done' => true,
        ]);
        $response->assertStatus(403);

        $updatedProtocol = Protocol::find($protocol->id);

        $this->assertFalse($updatedProtocol->done);
        $this->assertNull($updatedProtocol->comment);
    }
}
