<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\User;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $existing = Invoice::where('user_id', $user->id)->where('status', 'unpaid')->first();
            
            if (!$existing) {
                Invoice::create([
                    'user_id' => $user->id,
                    'invoice_code' => 'INV-' . strtoupper(uniqid()),
                    'service_name' => 'Jasa Pengiriman Batu Bata (270kg)',
                    'amount' => 2500000,
                    'status' => 'unpaid',
                    'due_date' => now()->addDays(7),
                ]);
            }
        }
    }
}