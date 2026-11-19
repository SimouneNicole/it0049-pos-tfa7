<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PosFormsAndUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_create_customer_form_renders_and_stores_valid_customer(): void
    {
        $createPage = $this->get('/customers/create');

        $createPage->assertOk();
        $createPage->assertSee('New Customer Account');
        $createPage->assertSee('name="full_name"', false);
        $createPage->assertSee('name="email"', false);
        $createPage->assertSee('name="phone"', false);

        $response = $this->post('/customers', [
            'full_name' => '  Mateo Villanueva  ',
            'email' => 'mateo.villanueva@store.ph',
            'phone' => '+63 917 444 8899',
        ]);

        $response->assertRedirect('/customers');
        $response->assertSessionHas('success', 'Customer account created successfully.');

        $this->assertDatabaseHas('customers', [
            'full_name' => 'Mateo Villanueva',
            'email' => 'mateo.villanueva@store.ph',
            'phone' => '+63 917 444 8899',
        ]);
    }

    public function test_create_customer_rejects_invalid_input_and_preserves_old_input(): void
    {
        $response = $this->from('/customers/create')->post('/customers', [
            'full_name' => '',
            'email' => 'not-an-email',
            'phone' => '+63 917 000 1234',
        ]);

        $response->assertRedirect('/customers/create');
        $response->assertSessionHasErrors(['full_name', 'email']);

        $followed = $this->followingRedirects()->from('/customers/create')->post('/customers', [
            'full_name' => '',
            'email' => 'not-an-email',
            'phone' => '+63 917 000 1234',
        ]);

        $followed->assertOk();
        $followed->assertSee('id="form-error-summary"', false);
        $followed->assertSee('Full name is required.');
        $followed->assertSee('Please enter a valid email address.');
        $followed->assertSee('value="not-an-email"', false);
        $followed->assertSee('value="+63 917 000 1234"', false);
        $followed->assertSee('aria-invalid="true"', false);
    }

    public function test_edit_and_update_customer_prefills_form_and_enforces_unique_email(): void
    {
        $customer = Customer::orderBy('id')->firstOrFail();
        $otherCustomer = Customer::where('id', '!=', $customer->id)->firstOrFail();

        $editPage = $this->get("/customers/{$customer->id}/edit");
        $editPage->assertOk();
        $editPage->assertSee('Edit Customer Account');
        $editPage->assertSee($customer->full_name);
        $editPage->assertSee($customer->email);

        $duplicateResp = $this->from("/customers/{$customer->id}/edit")->put("/customers/{$customer->id}", [
            'full_name' => $customer->full_name,
            'email' => $otherCustomer->email,
            'phone' => $customer->phone,
        ]);
        $duplicateResp->assertRedirect("/customers/{$customer->id}/edit");
        $duplicateResp->assertSessionHasErrors(['email']);

        $updateResp = $this->put("/customers/{$customer->id}", [
            'full_name' => 'Eleanor Vance-Cruz',
            'email' => $customer->email,
            'phone' => '+63 917 111 2233',
        ]);

        $updateResp->assertRedirect('/customers');
        $updateResp->assertSessionHas('success', 'Customer account updated successfully.');

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'full_name' => 'Eleanor Vance-Cruz',
            'phone' => '+63 917 111 2233',
        ]);
    }

    public function test_create_user_form_validates_required_unique_fields_and_inserts_record(): void
    {
        $createPage = $this->get('/users/create');

        $createPage->assertOk();
        $createPage->assertSee('New User Account');
        $createPage->assertSee('enctype="multipart/form-data"', false);
        $createPage->assertSee('name="username"', false);
        $createPage->assertSee('name="full_name"', false);
        $createPage->assertSee('name="avatar"', false);

        $failResp = $this->from('/users/create')->post('/users', [
            'username' => 'clara.delgado',
            'full_name' => '',
        ]);
        $failResp->assertRedirect('/users/create');
        $failResp->assertSessionHasErrors(['username', 'full_name']);

        $okResp = $this->post('/users', [
            'username' => 'cashier.delacruz',
            'full_name' => 'Marco Dela Cruz',
            'role' => 'Cashier',
        ]);

        $okResp->assertRedirect('/users');
        $okResp->assertSessionHas('success', 'User account created successfully.');

        $this->assertDatabaseHas('users', [
            'username' => 'cashier.delacruz',
            'full_name' => 'Marco Dela Cruz',
            'role' => 'Cashier',
        ]);
    }

    public function test_user_avatar_upload_processes_thumbnail_stores_on_public_disk_and_replaces_cleanly(): void
    {
        Storage::fake('public');

        $user = User::orderBy('id')->firstOrFail();

        $editPage = $this->get("/users/{$user->id}/edit");
        $editPage->assertOk();
        $editPage->assertSee('Edit User Account');
        $editPage->assertSee('avatar-placeholder.svg');
        $editPage->assertSee($user->username);

        $firstUpload = UploadedFile::fake()->image('staff-photo.jpg', 900, 500)->size(400);

        $firstResp = $this->put("/users/{$user->id}", [
            'username' => $user->username,
            'full_name' => 'Clara Delgado Sr.',
            'role' => 'System Administrator',
            'avatar' => $firstUpload,
        ]);

        $firstResp->assertRedirect('/users');
        $firstResp->assertSessionHas('success', 'User account updated successfully.');

        $user->refresh();
        $this->assertNotNull($user->avatar);
        $this->assertStringStartsWith('avatars/', $user->avatar);
        $this->assertStringEndsWith('.jpg', $user->avatar);
        Storage::disk('public')->assertExists($user->avatar);

        $processedInfo = getimagesizefromstring(Storage::disk('public')->get($user->avatar));
        $this->assertIsArray($processedInfo);
        $this->assertSame(256, $processedInfo[0]);
        $this->assertSame(256, $processedInfo[1]);

        $directoryPage = $this->get('/users');
        $directoryPage->assertOk();
        $directoryPage->assertSee($user->avatarUrl());
        $directoryPage->assertSee('avatar-placeholder.svg');

        $oldPath = $user->avatar;
        $secondUpload = UploadedFile::fake()->image('new-portrait.png', 640, 640)->size(200);

        $secondResp = $this->put("/users/{$user->id}", [
            'username' => $user->username,
            'full_name' => 'Clara Delgado Sr.',
            'role' => 'System Administrator',
            'avatar' => $secondUpload,
        ]);

        $secondResp->assertRedirect('/users');
        $user->refresh();

        $this->assertNotSame($oldPath, $user->avatar);
        $this->assertStringEndsWith('.png', $user->avatar);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($user->avatar);

        $removeResp = $this->put("/users/{$user->id}", [
            'username' => $user->username,
            'full_name' => 'Clara Delgado Sr.',
            'role' => 'System Administrator',
            'remove_avatar' => '1',
        ]);
        $removeResp->assertRedirect('/users');
        $currentPath = $user->avatar;
        $user->refresh();
        $this->assertNull($user->avatar);
        Storage::disk('public')->assertMissing($currentPath);
    }

    public function test_avatar_upload_rejects_oversized_files_and_non_image_files(): void
    {
        Storage::fake('public');

        $user = User::orderBy('id')->firstOrFail();

        $oversized = UploadedFile::fake()->image('large.jpg', 800, 800)->size(2500);
        $oversizedResp = $this->from("/users/{$user->id}/edit")->put("/users/{$user->id}", [
            'username' => $user->username,
            'full_name' => 'Clara Edited Name',
            'role' => $user->role,
            'avatar' => $oversized,
        ]);
        $oversizedResp->assertRedirect("/users/{$user->id}/edit");
        $oversizedResp->assertSessionHasErrors(['avatar']);

        $pdf = UploadedFile::fake()->create('document.pdf', 120, 'application/pdf');
        $pdfResp = $this->from("/users/{$user->id}/edit")->put("/users/{$user->id}", [
            'username' => $user->username,
            'full_name' => 'Clara Edited Name',
            'role' => $user->role,
            'avatar' => $pdf,
        ]);
        $pdfResp->assertRedirect("/users/{$user->id}/edit");
        $pdfResp->assertSessionHasErrors(['avatar']);

        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_tfa7_static_assets_and_placeholder_image_exist(): void
    {
        $this->assertFileExists(public_path('images/avatar-placeholder.svg'));
        $this->assertFileExists(public_path('js/app.js'));
        $this->assertTrue(file_exists(public_path('storage')) || is_link(public_path('storage')));
    }

    public function test_avatar_serving_route_serves_public_disk_avatar_and_blocks_traversal(): void
    {
        Storage::fake('public');

        $user = User::orderBy('id')->firstOrFail();
        $avatar = UploadedFile::fake()->image('route-check.png', 300, 300);

        $this->put("/users/{$user->id}", [
            'username' => $user->username,
            'full_name' => $user->full_name,
            'role' => $user->role,
            'avatar' => $avatar,
        ])->assertRedirect('/users');

        $user->refresh();
        $this->get('/avatars/'.basename($user->avatar))->assertOk();
        $this->get('/avatars/does-not-exist.png')->assertNotFound();
        $this->get('/avatars/../.env')->assertNotFound();
    }
}
