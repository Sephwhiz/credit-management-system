<?php

namespace Database\Seeders;

use App\Models\Loan;
use App\Models\LoanType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BorrowerSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Dedicated Admin Account
        User::firstOrCreate(
            ['email' => 'test@creditms.com'],
            [
                'name' => 'Test Administrator',
                'password' => Hash::make('Admin123!'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Loan Types Using ONLY 'name' Column (Matches Current Supabase Schema)
        $loanTypes = ['Personal Loan', 'Business Loan', 'Emergency Loan'];
        
        foreach ($loanTypes as $typeName) {
            LoanType::firstOrCreate(['name' => $typeName]);
        }

        // 3. Generate 12 Fake Borrowers With Realistic Loans
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

            $loanCount = fake()->numberBetween(3, 5);

            for ($j = 0; $j < $loanCount; $j++) {
                $selectedType = LoanType::inRandomOrder()->first();
                
                // Use DB::table() to avoid Eloquent fillable/mass assignment issues
                DB::table('loans')->insert([
                    'user_id' => $user->id,
                    'loan_type_id' => $selectedType->id,
                    'amount' => fake()->numberBetween(5000, 50000),
                    'purpose' => fake()->randomElement($purposes),
                    'status' => fake()->randomElement($statuses),
                    'created_at' => now()->subDays(fake()->numberBetween(1, 90)),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('✅ Seeder Updated: Created 1 Admin + 12 Borrowers with 36-60 loans!');
        $this->command->info('   Note: Used minimal schema fields compatible with current Supabase structure.');
    }
}