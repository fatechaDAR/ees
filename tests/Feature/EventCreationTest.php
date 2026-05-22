<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Event;

class EventCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_event_via_post_route()
    {
        $response = $this->post(route('event.store'), [
            'name' => 'Dies Natalis Kampus 2026',
            'date' => '2026-10-15',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('event.index'));
        $response->assertSessionHas('success', 'Event berhasil ditambahkan.');

        $this->assertDatabaseHas('events', [
            'name' => 'Dies Natalis Kampus 2026',
            'start_date' => '2026-10-15',
            'end_date' => '2026-10-15',
            'status' => 'active',
        ]);
    }

    public function test_event_creation_requires_name()
    {
        $response = $this->post(route('event.store'), [
            'name' => '',
            'date' => '2026-10-15',
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseCount('events', 0);
    }
}
