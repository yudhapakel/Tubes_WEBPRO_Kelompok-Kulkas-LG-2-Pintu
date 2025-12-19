<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penawarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('quotation_number')->unique();
            $table->text('description');
            $table->json('items');
            
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax_percentage', 5, 2);
            $table->decimal('tax_amount', 12, 2);
            $table->decimal('total', 12, 2);
            
            $table->decimal('client_counter_offer', 12, 2)->nullable();
            $table->text('client_notes')->nullable();
            $table->decimal('admin_counter_offer', 12, 2)->nullable();
            $table->text('admin_notes')->nullable();
            
            $table->enum('status', ['pending', 'sent', 'negotiating', 'accepted', 'rejected', 'converted'])->default('pending');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penawarans');
    }
};
