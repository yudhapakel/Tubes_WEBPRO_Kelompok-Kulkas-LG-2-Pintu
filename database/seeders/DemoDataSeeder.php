<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Document;
use App\Models\Penawaran;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        echo "Seeding demo data...\n\n";

        // 1. CREATE USERS
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin Triloka',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Jl. Telekomunikasi No. 1, Bandung'
            ]
        );
        echo "Admin created: {$admin->email} / admin123\n";

        $clients = [];
        $clientData = [
            ['name' => 'Budi Santoso', 'email' => 'budi@company.com', 'phone' => '081211111111'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti@startup.com', 'phone' => '081222222222'],
            ['name' => 'Ahmad Rizky', 'email' => 'ahmad@enterprise.com', 'phone' => '081233333333'],
        ];

        foreach ($clientData as $data) {
            $client = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => 'klien',
                    'phone' => $data['phone'],
                    'address' => 'Jakarta Selatan'
                ]
            );
            $clients[] = $client;
            echo "Client created: {$client->email} / password\n";
        }

        echo "\n";

        // 2. CREATE DOCUMENT REQUESTS (berbagai status)
        $documents = [];
        
        // Request 1: Pending (baru masuk, belum ada penawaran)
        $doc1 = Document::create([
            'user_id' => $clients[0]->id,
            'nama' => 'PT Maju Jaya',
            'alamat' => 'Jl. Sudirman No. 123',
            'telepon' => '081211111111',
            'jasa' => 'Website Development',
            'kota' => 'Jakarta',
            'prov' => 'DKI Jakarta',
            'negara' => 'Indonesia',
            'kodepos' => '12190',
            'client_budget' => 15000000,
            'file_path' => 'documents/dummy.pdf',
            'status' => 'pending'
        ]);
        $documents[] = $doc1;
        echo "Request 1: Pending (belum ada penawaran)\n";

        // Request 2: Ada penawaran yang dikirim ke client
        $doc2 = Document::create([
            'user_id' => $clients[1]->id,
            'nama' => 'CV Digital Solution',
            'alamat' => 'Jl. Gatot Subroto No. 45',
            'telepon' => '081222222222',
            'jasa' => 'Mobile App Development',
            'kota' => 'Bandung',
            'prov' => 'Jawa Barat',
            'negara' => 'Indonesia',
            'kodepos' => '40123',
            'client_budget' => 35000000,
            'file_path' => 'documents/dummy.pdf',
            'status' => 'quotation_created'
        ]);
        $penawaran1 = Penawaran::create([
            'document_id' => $doc2->id,
            'user_id' => $clients[1]->id,
            'quotation_number' => 'QOT-2025-001',
            'description' => 'Pembuatan aplikasi mobile untuk e-commerce dengan fitur lengkap',
            'items' => json_encode([
                ['type' => 'Services', 'name' => 'Mobile App Development (Android & iOS)', 'qty' => 1, 'unit_price' => 30000000, 'subtotal' => 30000000],
                ['type' => 'Services', 'name' => 'Backend API Development', 'qty' => 1, 'unit_price' => 8000000, 'subtotal' => 8000000],
                ['type' => 'Services', 'name' => 'UI/UX Design', 'qty' => 1, 'unit_price' => 5000000, 'subtotal' => 5000000],
            ]),
            'subtotal' => 43000000,
            'tax_percentage' => 11,
            'tax_amount' => 4730000,
            'total' => 47730000,
            'status' => 'sent'
        ]);
        echo "Request 2: Penawaran sent (menunggu response client)\n";

        // Request 3: Penawaran accepted, siap convert ke invoice
        $doc3 = Document::create([
            'user_id' => $clients[2]->id,
            'nama' => 'PT Enterprise Global',
            'alamat' => 'Jl. MH Thamrin No. 88',
            'telepon' => '081233333333',
            'jasa' => 'ERP System Implementation',
            'kota' => 'Surabaya',
            'prov' => 'Jawa Timur',
            'negara' => 'Indonesia',
            'kodepos' => '60123',
            'client_budget' => 80000000,
            'file_path' => 'documents/dummy.pdf',
            'status' => 'quotation_created'
        ]);
        $penawaran2 = Penawaran::create([
            'document_id' => $doc3->id,
            'user_id' => $clients[2]->id,
            'quotation_number' => 'QOT-2025-002',
            'description' => 'Implementasi sistem ERP terintegrasi dengan modul HR, Finance, dan Inventory',
            'items' => json_encode([
                ['type' => 'Software', 'name' => 'ERP License', 'qty' => 1, 'unit_price' => 50000000, 'subtotal' => 50000000],
                ['type' => 'Services', 'name' => 'Implementation & Customization', 'qty' => 1, 'unit_price' => 20000000, 'subtotal' => 20000000],
                ['type' => 'Services', 'name' => 'Training & Support (6 months)', 'qty' => 1, 'unit_price' => 10000000, 'subtotal' => 10000000],
            ]),
            'subtotal' => 80000000,
            'tax_percentage' => 11,
            'tax_amount' => 8800000,
            'total' => 88800000,
            'status' => 'accepted'
        ]);
        echo "Request 3: Penawaran accepted (siap convert ke invoice)\n";

        // Request 4: Sudah ada invoice, menunggu payment
        $doc4 = Document::create([
            'user_id' => $clients[0]->id,
            'nama' => 'Startup Innovation',
            'alamat' => 'Jl. Veteran No. 15',
            'telepon' => '081211111111',
            'jasa' => 'Cloud Infrastructure Setup',
            'kota' => 'Jakarta',
            'prov' => 'DKI Jakarta',
            'negara' => 'Indonesia',
            'kodepos' => '10110',
            'client_budget' => 25000000,
            'file_path' => 'documents/dummy.pdf',
            'status' => 'quotation_created'
        ]);
        $penawaran3 = Penawaran::create([
            'document_id' => $doc4->id,
            'user_id' => $clients[0]->id,
            'quotation_number' => 'QOT-2025-003',
            'description' => 'Setup cloud infrastructure dengan AWS EC2, RDS, dan S3',
            'items' => json_encode([
                ['type' => 'Services', 'name' => 'Cloud Infrastructure Setup', 'qty' => 1, 'unit_price' => 15000000, 'subtotal' => 15000000],
                ['type' => 'Services', 'name' => 'Migration & Deployment', 'qty' => 1, 'unit_price' => 8000000, 'subtotal' => 8000000],
            ]),
            'subtotal' => 23000000,
            'tax_percentage' => 11,
            'tax_amount' => 2530000,
            'total' => 25530000,
            'status' => 'converted'
        ]);
        $invoice1 = Invoice::create([
            'quotation_id' => $penawaran3->id,
            'user_id' => $clients[0]->id,
            'invoice_number' => 'INV-2025-001',
            'items' => $penawaran3->items,
            'subtotal' => $penawaran3->subtotal,
            'tax_amount' => $penawaran3->tax_amount,
            'total' => $penawaran3->total,
            'status' => 'pending',
            'issue_date' => now(),
            'due_date' => now()->addDays(30)
        ]);
        echo "Request 4: Invoice created (menunggu client bayar)\n";

        // Request 5: Ada payment pending verification
        $doc5 = Document::create([
            'user_id' => $clients[1]->id,
            'nama' => 'Toko Online ABC',
            'alamat' => 'Jl. Braga No. 99',
            'telepon' => '081222222222',
            'jasa' => 'E-Commerce Website',
            'kota' => 'Bandung',
            'prov' => 'Jawa Barat',
            'negara' => 'Indonesia',
            'kodepos' => '40111',
            'client_budget' => 12000000,
            'file_path' => 'documents/dummy.pdf',
            'status' => 'quotation_created'
        ]);
        $penawaran4 = Penawaran::create([
            'document_id' => $doc5->id,
            'user_id' => $clients[1]->id,
            'quotation_number' => 'QOT-2025-004',
            'description' => 'Pembuatan website e-commerce dengan payment gateway',
            'items' => json_encode([
                ['type' => 'Services', 'name' => 'E-Commerce Website Development', 'qty' => 1, 'unit_price' => 12000000, 'subtotal' => 12000000],
            ]),
            'subtotal' => 12000000,
            'tax_percentage' => 11,
            'tax_amount' => 1320000,
            'total' => 13320000,
            'status' => 'converted'
        ]);
        $invoice2 = Invoice::create([
            'quotation_id' => $penawaran4->id,
            'user_id' => $clients[1]->id,
            'invoice_number' => 'INV-2025-002',
            'items' => $penawaran4->items,
            'subtotal' => $penawaran4->subtotal,
            'tax_amount' => $penawaran4->tax_amount,
            'total' => $penawaran4->total,
            'status' => 'pending',
            'issue_date' => now()->subDays(5),
            'due_date' => now()->addDays(25)
        ]);
        $payment1 = Payment::create([
            'invoice_id' => $invoice2->id,
            'user_id' => $clients[1]->id,
            'payment_type' => 'full',
            'amount' => $invoice2->total,
            'proof_file' => 'payments/dummy-proof.jpg',
            'status' => 'pending'
        ]);
        echo "Request 5: Payment pending verification\n";

        echo "\n";
        echo "\nDemo data seeding complete!\n\n";
        echo "=" . str_repeat("=", 60) . "\n";
        echo "  SUMMARY\n";
        echo "=" . str_repeat("=", 60) . "\n";
        echo "  Admin: admin@admin.com / admin123\n";
        echo "  Clients: 3 users (password: password)\n";
        echo "  Requests: 5 documents\n";
        echo "  Penawarans: 4 quotations\n";
        echo "  Invoices: 2 invoices\n";
        echo "  Payments: 1 pending verification\n";
        echo "=" . str_repeat("=", 60) . "\n\n";
        
        echo "Login URL: http://localhost:8000\n";
        echo "Admin Panel: http://localhost:8000/admin/dashboard\n\n";
    }
}
