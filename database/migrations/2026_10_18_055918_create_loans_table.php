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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('loan_type_id')->constrained()->onDelete('restrict');
            
            // Financial Fields
            $table->decimal('amount', 12, 2); // Principal amount
            $table->decimal('custom_interest_rate', 5, 2)->nullable(); // Admin override rate
            $table->decimal('calculated_interest_amount', 12, 2)->default(0); // Computed simple interest
            $table->decimal('total_repayable_amount', 12, 2)->default(0); // Principal + Interest
            
            // Loan Details
            $table->text('purpose')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->date('due_date')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};