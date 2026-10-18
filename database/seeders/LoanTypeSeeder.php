<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LoanType;

class LoanTypeSeeder extends Seeder
{
    public function run(): void
    {
        LoanType::insert([
            [
                'name' => 'Personal Loan', 
                'default_interest_rate' => 5.00, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'name' => 'Business Loan', 
                'default_interest_rate' => 8.50, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'name' => 'Emergency Loan', 
                'default_interest_rate' => 3.00, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);
    }
}