<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Division;
use App\Models\User;
use App\Models\CommitteeMember;

class CommitteeFilterTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $event1;
    private $event2;
    private $division1;
    private $division2;
    private $panitia1;
    private $panitia2;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Admin
        $this->user = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);

        // Events
        $this->event1 = Event::create([
            'name' => 'Dies Natalis 64',
            'start_date' => '2026-10-15',
            'end_date' => '2026-10-15',
            'status' => 'active',
        ]);

        $this->event2 = Event::create([
            'name' => 'Dies Natalis 65',
            'start_date' => '2027-10-15',
            'end_date' => '2027-10-15',
            'status' => 'active',
        ]);

        // Divisions
        $this->division1 = Division::create([
            'event_id' => $this->event1->id,
            'name' => 'Divisi Acara 64',
            'description' => 'Bagian Acara',
        ]);

        $this->division2 = Division::create([
            'event_id' => $this->event2->id,
            'name' => 'Divisi Logistik 65',
            'description' => 'Bagian Logistik',
        ]);

        // Panitia Users
        $this->panitia1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@test.com',
            'role' => 'panitia',
            'password' => bcrypt('password123'),
        ]);

        $this->panitia2 = User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@test.com',
            'role' => 'panitia',
            'password' => bcrypt('password123'),
        ]);

        // Committee Members
        CommitteeMember::create([
            'user_id' => $this->panitia1->id,
            'division_id' => $this->division1->id,
            'position' => 'lead',
        ]);

        CommitteeMember::create([
            'user_id' => $this->panitia2->id,
            'division_id' => $this->division2->id,
            'position' => 'member',
        ]);
    }

    public function test_can_view_panitia_filtered_by_selected_event()
    {
        // View event 1 (Dies Natalis 64)
        $response = $this->actingAs($this->user)->get(route('panitia.index', ['event_id' => $this->event1->id]));
        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('Divisi Acara 64');
        $response->assertDontSee('Siti Aminah');

        // View event 2 (Dies Natalis 65)
        $response = $this->actingAs($this->user)->get(route('panitia.index', ['event_id' => $this->event2->id]));
        $response->assertStatus(200);
        $response->assertSee('Siti Aminah');
        $response->assertSee('Divisi Logistik 65');
        $response->assertDontSee('Budi Santoso');
    }

    public function test_can_search_panitia_by_name()
    {
        // Add one more member to event 1
        $panitia3 = User::create([
            'name' => 'Agus Raharjo',
            'email' => 'agus@test.com',
            'role' => 'panitia',
            'password' => bcrypt('password123'),
        ]);

        CommitteeMember::create([
            'user_id' => $panitia3->id,
            'division_id' => $this->division1->id,
            'position' => 'member',
        ]);

        // Search for 'Budi' on event 1
        $response = $this->actingAs($this->user)->get(route('panitia.index', [
            'event_id' => $this->event1->id,
            'search' => 'Budi'
        ]));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Agus Raharjo');

        // Search for 'Agus' on event 1
        $response = $this->actingAs($this->user)->get(route('panitia.index', [
            'event_id' => $this->event1->id,
            'search' => 'Agus'
        ]));

        $response->assertStatus(200);
        $response->assertSee('Agus Raharjo');
        $response->assertDontSee('Budi Santoso');
    }

    public function test_can_filter_panitia_by_division()
    {
        $division1_alt = Division::create([
            'event_id' => $this->event1->id,
            'name' => 'Divisi Pubdok 64',
            'description' => 'Pubdok 64',
        ]);

        $panitia3 = User::create([
            'name' => 'Agus Raharjo',
            'email' => 'agus@test.com',
            'role' => 'panitia',
            'password' => bcrypt('password123'),
        ]);

        CommitteeMember::create([
            'user_id' => $panitia3->id,
            'division_id' => $division1_alt->id,
            'position' => 'member',
        ]);

        // Filter by Divisi Acara 64
        $response = $this->actingAs($this->user)->get(route('panitia.index', [
            'event_id' => $this->event1->id,
            'division_id' => $this->division1->id
        ]));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Agus Raharjo');

        // Filter by Divisi Pubdok 64
        $response = $this->actingAs($this->user)->get(route('panitia.index', [
            'event_id' => $this->event1->id,
            'division_id' => $division1_alt->id
        ]));

        $response->assertStatus(200);
        $response->assertSee('Agus Raharjo');
        $response->assertDontSee('Budi Santoso');
    }

    public function test_can_export_panitia_as_csv()
    {
        $response = $this->actingAs($this->user)->get(route('panitia.export', [
            'event_id' => $this->event1->id,
            'search' => 'Budi'
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        
        $content = $response->streamedContent();
        $this->assertStringContainsString('Budi Santoso', $content);
        $this->assertStringContainsString('budi@test.com', $content);
        $this->assertStringContainsString('Divisi Acara 64', $content);
    }
}
