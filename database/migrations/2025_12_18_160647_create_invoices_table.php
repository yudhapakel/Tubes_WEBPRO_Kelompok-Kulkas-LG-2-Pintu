<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('penawarans')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Invoice details
            $table->string('invoice_number')->unique(); // INV-2025-001
            $table->json('items'); // Copied from quotation
            
            // Pricing (final agreed amount)
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax_amount', 12, 2);
            $table->decimal('total', 12, 2);
            
            // Status (simplified - full payment only)
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            
            // Dates
            $table->date('issue_date');
            $table->date('due_date');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
