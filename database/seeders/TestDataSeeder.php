<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Document;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin Triloka',
                'password' => bcrypt('admin123'),
                'role' => 'admin'
            ]
        );

        echo "Admin: {$admin->email}\n";

        $client = User::firstOrCreate(
            ['email' => 'client@test.com'],
            [
                'name' => 'Test Client',
                'password' => bcrypt('password'),
                'role' => 'klien'
            ]
        );

        echo "Client: {$client->email}\n";

        $doc = Document::create([
            'user_id' => $client->id,
            'nama' => 'PT Maju Jaya',
            'alamat' => 'Jl. Sudirman No. 123',
            'telepon' => '08123456789',
            'jasa' => 'Website Development',
            'kota' => 'Jakarta',
            'prov' => 'DKI Jakarta',
            'negara' => 'Indonesia',
            'kodepos' => '12190',
            'file_path' => 'documents/dummy.pdf',
            'status' => 'pending'
        ]);

        echo "Request created: ID #{$doc->id}\n";
        echo "\n Login: admin@admin.com / admin123\n";
        echo " URL: http://localhost:8000/admin/dashboard\n";
    }
}
