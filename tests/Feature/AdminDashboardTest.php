<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_loads_for_admin_users(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@shopease.com',
            'role' => 'admin',
            'status' => true,
        ]);

        $this->actingAs($user, 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard');
    }

    public function test_admin_reviews_page_loads_for_admin_users(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin2@shopease.com',
            'role' => 'admin',
            'status' => true,
        ]);

        $this->actingAs($user, 'admin')
            ->get(route('admin.reviews.index'))
            ->assertOk()
            ->assertSee('Reviews');
    }

    public function test_admin_settings_page_only_shows_general_and_admin_account_sections(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin3@shopease.com',
            'role' => 'admin',
            'status' => true,
        ]);

        $this->actingAs($user, 'admin')
            ->get(route('admin.settings.index'))
            ->assertOk()
            ->assertSee('General settings')
            ->assertSee('Admin email')
            ->assertSee('Admin password')
            ->assertDontSee('Store information')
            ->assertDontSee('Shipping')
            ->assertDontSee('Payment');
    }

    public function test_product_image_upload_size_is_under_5mb(): void
    {
        $request = new \App\Http\Requests\Admin\StoreProductRequest();
        $rules = $request->rules();

        $this->assertSame(['nullable', 'image', 'max:5120'], $rules['main_image']);
        $this->assertSame(['nullable', 'image', 'max:5120'], $rules['gallery.*']);
    }

    public function test_supabase_storage_is_configured_for_path_style_s3_uploads(): void
    {
        $this->assertTrue(config('filesystems.disks.supabase.use_path_style_endpoint'));
    }

    public function test_checkout_has_billing_and_cod_options(): void
    {
        $this->assertStringContainsString("name=\"same_as_billing\"", file_get_contents(resource_path('views/user/checkout.blade.php')));
        $this->assertStringContainsString("value=\"cod\"", file_get_contents(resource_path('views/user/checkout.blade.php')));
    }
}
