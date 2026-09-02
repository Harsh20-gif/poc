<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_client_directly_from_the_clients_directory(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        $response = $this->post(route('clients.store'), [
            'company_name' => 'Acme Labs',
            'owner' => 'Bhavya Sharma',
            'address' => '12 Market Road',
            'city' => 'Delhi',
            'state' => 'Delhi',
            'zip' => '110001',
            'country' => 'India',
            'phone' => '+91 9876543210',
            'website' => 'https://acme.example',
            'vat_number' => 'IND123456789',
            'client_group' => 'Corporate',
            'currency' => 'INR',
            'currency_symbol' => '₹',
        ]);

        $response->assertRedirect(route('clients.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('clients', [
            'company_name' => 'Acme Labs',
            'owner' => 'Bhavya Sharma',
            'city' => 'Delhi',
            'currency' => 'INR',
            'currency_symbol' => '₹',
        ]);

        $this->assertDatabaseHas('clients', [
            'lead_id' => null,
        ]);

        $this->assertEquals(1, Client::count());
    }
}
