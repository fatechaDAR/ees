<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Division;
use App\Models\User;

class DivisionManagementTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create an admin user to act as authenticated user
        $this->user = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);
    }

    public function test_can_view_divisions_of_selected_event()
    {
        $event1 = new Event([
            'name' => 'Event Satu',
            'start_date' => '2026-10-15',
            'end_date' => '2026-10-15',
            'status' => 'active',
        ]);
        $event1->created_at = now()->subDays(2);
        $event1->save();

        $event2 = new Event([
            'name' => 'Event Dua',
            'start_date' => '2026-10-16',
            'end_date' => '2026-10-16',
            'status' => 'active',
        ]);
        $event2->created_at = now();
        $event2->save();

        $division1 = Division::create([
            'event_id' => $event1->id,
            'name' => 'Divisi Acara Event 1',
            'description' => 'Deskripsi Event 1',
        ]);

        $division2 = Division::create([
            'event_id' => $event2->id,
            'name' => 'Divisi Logistik Event 2',
            'description' => 'Deskripsi Event 2',
        ]);

        // Default to latest event
        $response = $this->actingAs($this->user)->get(route('divisi.index'));
        $response->assertStatus(200);
        // Latest event is event2 (created last)
        $response->assertSee($division2->name);
        $response->assertDontSee($division1->name);

        // Request specific event
        $response = $this->actingAs($this->user)->get(route('divisi.index', ['event_id' => $event1->id]));
        $response->assertStatus(200);
        $response->assertSee($division1->name);
        $response->assertDontSee($division2->name);
    }

    public function test_can_create_division()
    {
        $event = Event::create([
            'name' => 'Event Test',
            'start_date' => '2026-10-15',
            'end_date' => '2026-10-15',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->post(route('divisi.store'), [
            'name' => 'Divisi Konsumsi',
            'description' => 'Mengurus konsumsi panitia dan peserta.',
            'event_id' => $event->id,
        ]);

        $response->assertRedirect(route('divisi.index', ['event_id' => $event->id]));
        $response->assertSessionHas('success', 'Divisi berhasil ditambahkan.');

        $this->assertDatabaseHas('divisions', [
            'name' => 'Divisi Konsumsi',
            'description' => 'Mengurus konsumsi panitia dan peserta.',
            'event_id' => $event->id,
        ]);
    }

    public function test_create_division_validation()
    {
        $event = Event::create([
            'name' => 'Event Test',
            'start_date' => '2026-10-15',
            'end_date' => '2026-10-15',
            'status' => 'active',
        ]);

        // Validation error: name too short (min:3)
        $response = $this->actingAs($this->user)->post(route('divisi.store'), [
            'name' => 'Ab',
            'description' => 'Test',
            'event_id' => $event->id,
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseCount('divisions', 0);
    }

    public function test_can_update_division()
    {
        $event = Event::create([
            'name' => 'Event Test',
            'start_date' => '2026-10-15',
            'end_date' => '2026-10-15',
            'status' => 'active',
        ]);

        $division = Division::create([
            'event_id' => $event->id,
            'name' => 'Divisi Awal',
            'description' => 'Deskripsi Awal',
        ]);

        $response = $this->actingAs($this->user)->put(route('divisi.update', $division->id), [
            'name' => 'Divisi Baru',
            'description' => 'Deskripsi Baru',
            'event_id' => $event->id,
        ]);

        $response->assertRedirect(route('divisi.index', ['event_id' => $event->id]));
        $response->assertSessionHas('success', 'Divisi berhasil diperbarui.');

        $this->assertDatabaseHas('divisions', [
            'id' => $division->id,
            'name' => 'Divisi Baru',
            'description' => 'Deskripsi Baru',
        ]);
    }

    public function test_can_delete_division()
    {
        $event = Event::create([
            'name' => 'Event Test',
            'start_date' => '2026-10-15',
            'end_date' => '2026-10-15',
            'status' => 'active',
        ]);

        $division = Division::create([
            'event_id' => $event->id,
            'name' => 'Divisi Dihapus',
            'description' => 'Akan dihapus',
        ]);

        $response = $this->actingAs($this->user)->delete(route('divisi.destroy', $division->id));

        $response->assertRedirect(route('divisi.index', ['event_id' => $event->id]));
        $response->assertSessionHas('success', 'Divisi berhasil dihapus.');

        $this->assertDatabaseMissing('divisions', [
            'id' => $division->id,
        ]);
    }
}
