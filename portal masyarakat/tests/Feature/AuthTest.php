<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_and_registration_pages_are_available_to_guests(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Selamat datang kembali')
            ->assertSee('guest-back')
            ->assertSee('auth-submit');

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Buat akun masyarakat')
            ->assertSee('guest-back')
            ->assertSee('auth-submit');
    }

    public function test_demo_accounts_are_seeded_for_each_role(): void
    {
        Artisan::call('db:seed', ['--class' => DatabaseSeeder::class]);
        Artisan::call('db:seed', ['--class' => DatabaseSeeder::class]);

        $user = User::where('email', 'user@example.com')->firstOrFail();
        $admin = User::where('email', 'admin@example.com')->firstOrFail();

        $this->assertSame('user', $user->role);
        $this->assertSame('admin', $admin->role);
        $this->assertTrue(Hash::check('12345678', $user->password));
        $this->assertTrue(Hash::check('12345678', $admin->password));
        $this->assertSame(2, User::whereIn('email', ['user@example.com', 'admin@example.com'])->count());
    }

    public function test_a_visitor_can_register_as_a_user_but_cannot_choose_admin_role(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Warga Baru',
            'email' => 'warga@example.com',
            'password' => 'password-kuat',
            'password_confirmation' => 'password-kuat',
            'role' => 'admin',
        ]);

        $user = User::where('email', 'warga@example.com')->firstOrFail();

        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertSame('user', $user->role);
        $this->assertTrue(Hash::check('password-kuat', $user->password));
    }

    public function test_a_user_can_log_in_and_cannot_access_the_admin_dashboard(): void
    {
        $user = User::factory()->create();
        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('user.dashboard'));

        $this->get(route('home'))->assertRedirect(route('dashboard'));
        $this->get(route('dashboard'))->assertRedirect(route('user.dashboard'));

        $this->get(route('admin.dashboard'))->assertForbidden();
        $this->get(route('user.dashboard'))
            ->assertOk()
            ->assertSee('Buat laporan baru')
            ->assertSee('Lihat laporan saya')
            ->assertSee('Keluar dari akun');

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_an_admin_is_redirected_to_the_admin_dashboard(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();
        $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get(route('home'))->assertRedirect(route('dashboard'));
        $this->get(route('dashboard'))->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Semua pengaduan')
            ->assertSee('Keluar dari akun');
        $this->get(route('user.dashboard'))->assertForbidden();
    }

    public function test_admin_can_view_and_search_all_registered_users(): void
    {
        $admin = User::factory()->create(['name' => 'Admin Portal', 'role' => 'admin']);
        $firstUser = User::factory()->create(['name' => 'Siti Warga', 'email' => 'siti@example.com']);
        User::factory()->create(['name' => 'Budi Warga', 'email' => 'budi@example.com']);

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Semua pengguna')
            ->assertSee('siti@example.com')
            ->assertSee('budi@example.com')
            ->assertSee('Admin Portal');

        $this->get(route('admin.users.index', ['q' => 'Siti']))
            ->assertOk()
            ->assertSee('siti@example.com')
            ->assertDontSee('budi@example.com');

        $this->assertDatabaseHas('users', ['id' => $firstUser->id]);
    }

    public function test_regular_users_cannot_view_the_admin_user_directory(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_invalid_credentials_do_not_authenticate(): void
    {
        $response = $this->from(route('login'))->post(route('login.store'), [
            'email' => 'unknown@example.com',
            'password' => 'incorrect',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_a_user_can_submit_and_view_their_complaint(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('complaints.store'), [
            'subject' => 'Lampu jalan mati',
            'category' => 'infrastructure',
            'description' => 'Lampu jalan di depan balai warga sudah mati sejak kemarin.',
        ])->assertRedirect(route('user.complaints.index'));

        $complaint = Complaint::where('subject', 'Lampu jalan mati')->firstOrFail();
        Complaint::create([
            'user_id' => $otherUser->id,
            'subject' => 'Laporan warga lain',
            'category' => 'other',
            'description' => 'Ini adalah laporan milik warga yang lain.',
        ]);

        $this->assertSame($user->id, $complaint->user_id);
        $this->assertSame('pending', $complaint->status);

        $this->get(route('user.dashboard'))
            ->assertOk()
            ->assertSee('Buat laporan baru')
            ->assertSee('Lihat laporan saya')
            ->assertDontSee('Lampu jalan mati')
            ->assertDontSee('Laporan warga lain');

        $this->get(route('user.complaints.index'))
            ->assertOk()
            ->assertSee('Lampu jalan mati')
            ->assertDontSee('Laporan warga lain');

        $this->get(route('user.complaints.create'))
            ->assertOk()
            ->assertSee('Buat laporan baru');
    }

    public function test_users_can_filter_their_complaints_and_cancel_only_pending_complaints_they_own(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $pending = Complaint::create([
            'user_id' => $user->id,
            'subject' => 'Lampu taman mati',
            'category' => 'infrastructure',
            'description' => 'Lampu taman di lingkungan kami sudah mati.',
        ]);
        Complaint::create([
            'user_id' => $user->id,
            'subject' => 'Sampah belum diangkut',
            'category' => 'environment',
            'description' => 'Sampah belum diangkut selama beberapa hari.',
            'status' => 'resolved',
        ]);
        Complaint::create([
            'user_id' => $otherUser->id,
            'subject' => 'Laporan rahasia warga lain',
            'category' => 'other',
            'description' => 'Laporan ini milik pengguna lain.',
        ]);

        $this->actingAs($user)
            ->get(route('user.complaints.index', ['q' => 'Lampu', 'status_filter' => 'pending']))
            ->assertOk()
            ->assertSee('Lampu taman mati')
            ->assertDontSee('Sampah belum diangkut')
            ->assertDontSee('Laporan rahasia warga lain');

        $this->delete(route('complaints.destroy', $pending))
            ->assertRedirect(route('user.complaints.index'));

        $this->assertDatabaseMissing('complaints', ['id' => $pending->id]);
    }

    public function test_users_cannot_cancel_another_users_or_non_pending_complaint(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $otherComplaint = Complaint::create([
            'user_id' => $otherUser->id,
            'subject' => 'Pengaduan milik orang lain',
            'category' => 'other',
            'description' => 'Ini bukan pengaduan milik pengguna ini.',
        ]);
        $resolvedComplaint = Complaint::create([
            'user_id' => $user->id,
            'subject' => 'Pengaduan selesai',
            'category' => 'other',
            'description' => 'Pengaduan ini sudah selesai ditangani.',
            'status' => 'resolved',
        ]);

        $this->actingAs($user)
            ->delete(route('complaints.destroy', $otherComplaint))
            ->assertNotFound();

        $this->delete(route('complaints.destroy', $resolvedComplaint))
            ->assertForbidden();

        $this->assertDatabaseHas('complaints', ['id' => $otherComplaint->id]);
        $this->assertDatabaseHas('complaints', ['id' => $resolvedComplaint->id]);
    }

    public function test_admin_can_search_and_filter_complaints_and_change_priority(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();
        $reporter = User::factory()->create(['name' => 'Siti Pelapor']);
        $complaint = Complaint::create([
            'user_id' => $reporter->id,
            'subject' => 'Jalan berlubang',
            'category' => 'infrastructure',
            'description' => 'Ada lubang besar di jalan utama dekat pasar.',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard', [
                'q' => 'Siti',
                'status_filter' => 'pending',
                'category_filter' => 'infrastructure',
                'priority_filter' => 'normal',
            ]))
            ->assertOk()
            ->assertSee('Jalan berlubang');

        $this->patch(route('admin.complaints.update', $complaint), [
            'status' => 'in_progress',
            'priority' => 'high',
            'admin_response' => 'Laporan sedang diperiksa.',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'status' => 'in_progress',
            'priority' => 'high',
            'admin_response' => 'Laporan sedang diperiksa.',
        ]);
    }

    public function test_an_admin_can_update_complaint_status_and_response(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();
        $complaint = Complaint::create([
            'user_id' => User::factory()->create()->id,
            'subject' => 'Jalan berlubang',
            'category' => 'infrastructure',
            'description' => 'Ada lubang besar di jalan utama dekat pasar.',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.complaints.update', $complaint), [
                'status' => 'in_progress',
                'priority' => 'normal',
                'admin_response' => 'Laporan sudah diteruskan ke petugas terkait.',
            ])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'status' => 'in_progress',
            'admin_response' => 'Laporan sudah diteruskan ke petugas terkait.',
        ]);
    }
}
