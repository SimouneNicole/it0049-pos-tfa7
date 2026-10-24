<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosFoundationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seed the database before each test to simulate runtime state.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test the landing/home page loads with HTTP 200 and key elements.
     */
    public function test_home_page_returns_ok_and_displays_elements(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Point of Sale');
        $response->assertSee('Customer and staff records, kept in one clear place.');
        $response->assertSee('nav-capsule');
        $response->assertSee('glass-lens');
    }

    /**
     * Test the about page loads with HTTP 200 and description.
     */
    public function test_about_page_returns_ok_and_displays_elements(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('About');
        $response->assertSee('A simple place to view customer contact details and staff roles');
        $response->assertSee('nav-capsule');
    }

    /**
     * Test the customers directory renders with records retrieved via Eloquent.
     */
    public function test_customers_page_returns_ok_and_renders_records(): void
    {
        $response = $this->get('/customers');

        $response->assertStatus(200);
        $response->assertSee('Customer Accounts');
        $response->assertSee('Eleanor Vance');
        $response->assertSee('eleanor.vance@example.com');
        $response->assertSee('+1 (555) 234-5678');
        $response->assertSee('Marcus Thorne');
        $response->assertSee('Sophia Sterling');
        $response->assertSee('Lucas Gallagher');
        $response->assertSee('Clara Delgado');
        $response->assertSee('Julian Hayes');
    }

    /**
     * Test the users directory renders with staff records retrieved via Eloquent.
     */
    public function test_users_page_returns_ok_and_renders_staff_records(): void
    {
        $response = $this->get('/users');

        $response->assertStatus(200);
        $response->assertSee('User Accounts');
        $response->assertSee('clara.delgado');
        $response->assertSee('Clara Delgado');
        $response->assertSee('System Administrator');
        $response->assertSee('marcus.thorne');
        $response->assertSee('Store Manager');
        $response->assertSee('eleanor.vance');
        $response->assertSee('Head Cashier');
        $response->assertSee('lucas.gallagher');
        $response->assertSee('sophia.sterling');
        $response->assertSee('julian.hayes');
    }

    /**
     * Test that database seeders populate at least 5 sample records in both tables.
     */
    public function test_seeders_populate_minimum_five_records_per_table(): void
    {
        $customerCount = Customer::count();
        $userCount = User::count();

        $this->assertGreaterThanOrEqual(5, $customerCount, 'The customers table must contain at least 5 seeded records.');
        $this->assertGreaterThanOrEqual(5, $userCount, 'The users table must contain at least 5 seeded records.');
    }

    /**
     * Test Eloquent model CRUD and attribute casting behavior.
     */
    public function test_eloquent_models_function_properly(): void
    {
        $newCustomer = Customer::create([
            'full_name' => 'Gwen Stacy',
            'email'     => 'gwen.stacy@example.com',
            'phone'     => '+1 (555) 999-8888',
        ]);
        $this->assertDatabaseHas('customers', [
            'email' => 'gwen.stacy@example.com',
        ]);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $newCustomer->created_at);

        $newUser = User::create([
            'username'  => 'miles.morales',
            'full_name' => 'Miles Morales',
            'role'      => 'Inventory Assistant',
        ]);
        $this->assertDatabaseHas('users', [
            'username' => 'miles.morales',
        ]);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $newUser->created_at);
    }

    /**
     * Test that an invalid route properly yields 404 Not Found.
     */
    public function test_undefined_route_returns_404(): void
    {
        $response = $this->get('/non-existent-page-url');

        $response->assertStatus(404);
    }
}
