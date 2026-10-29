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
        // 1. Create Dedicated Admin Account for Grading
        User::firstOrCreate(
            ['email' => 'test@creditms.com'],
            [
                'name' => 'Test Administrator',
                'password' => Hash::make('Admin123!'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Loan Types (Matches Production Schema)
        $loanTypes = [
            ['name' => 'Personal Loan', 'interest_rate' => 5.0, 'min_amount' => 5000, 'max_amount' => 50000, 'term_months' => 12],
            ['name' => 'Business Loan', 'interest_rate' => 7.0, 'min_amount' => 10000, 'max_amount' => 100000, 'term_months' => 24],
            ['name' => 'Emergency Loan', 'interest_rate' => 6.0, 'min_amount' => 3000, 'max_amount' => 20000, 'term_months' => 6],
        ];

        foreach ($loanTypes as $type) {
            LoanType::firstOrCreate(['name' => $type['name']], $type);
        }

        // 3. Generate 12 Fake Borrowers with Realistic Loans
        $statuses = ['pending', 'approved', 'rejected', 'overdue'];
        $purposes = [
            'Home renovation', 'Medical emergency', 'Business expansion',
            'Education fees', 'Debt consolidation', 'Vehicle purchase',
            'Wedding expenses', 'Travel fund', 'Agricultural inputs'
        ];

        for ($i = 1; $i <= 12; $i++) {
            $user = User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('Password123!'),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]);

            // Each borrower gets 3-5 loans to ensure we hit 30+ total
            $loanCount = fake()->numberBetween(3, 5);

            for ($j = 0; $j < $loanCount; $j++) {
                $selectedType = LoanType::inRandomOrder()->first();
                
                Loan::create([
                    'user_id' => $user->id,
                    'loan_type_id' => $selectedType->id,
                    'amount' => fake()->numberBetween(5000, 50000),
                    'purpose' => fake()->randomElement($purposes),
                    'status' => fake()->randomElement($statuses),
                    'term_months' => $selectedType->term_months,
                    'due_date' => now()->addMonths(fake()->numberBetween(1, 24))->format('Y-m-d'),
                    'created_at' => now()->subDays(fake()->numberBetween(1, 90)),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('✅ Seeder Updated: Created 1 Admin + 12 Borrowers with 36-60 loans!');
    }
}