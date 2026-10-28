<?php

namespace Database\Seeders;

use App\Models\Loan;
use App\Models\LoanType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BorrowerSeeder extends Seeder
{
    public function run(): void
    {
        // Create loan type using ONLY the 'name' column available in local SQLite
        $loanType = LoanType::firstOrCreate(
            ['name' => 'Personal Loan']
        );

        $statuses = ['pending', 'approved', 'rejected'];
        $purposes = [
            'Home renovation',
            'Medical emergency',
            'Business expansion',
            'Education fees',
            'Debt consolidation',
            'Vehicle purchase',
            'Wedding expenses',
            'Travel fund',
        ];

        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('Password123!'),
                'email_verified_at' => now(),
            ]);

            $loanCount = fake()->numberBetween(3, 5);

            for ($j = 0; $j < $loanCount; $j++) {
                Loan::create([
                    'user_id' => $user->id,
                    'loan_type_id' => $loanType->id,
                    'amount' => fake()->numberBetween(500, 50000),
                    'purpose' => fake()->randomElement($purposes),
                    'status' => fake()->randomElement($statuses),
                    'due_date' => now()->addMonths(12)->format('Y-m-d'),
                ]);
            }
        }

        $this->command->info('✅ Created 10 borrowers with 30-50 total loans!');
    }
}