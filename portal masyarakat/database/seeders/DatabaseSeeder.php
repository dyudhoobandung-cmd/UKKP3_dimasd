<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new RuntimeException('Demo accounts can only be seeded in local or testing environments.');
        }

        $this->seedAccount('Pengguna Tes', 'user@example.com', 'user123', 'user');
        $this->seedAccount('Admin Tes', 'admin@example.com', 'admin123', 'admin');
    }

    private function seedAccount(string $name, string $email, string $password, string $role): void
    {
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
            ]
        );

        $user->forceFill(['role' => $role])->save();
    }
}
