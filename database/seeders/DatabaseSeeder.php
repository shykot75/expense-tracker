<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Main User
        $user = User::updateOrCreate(
            ['email' => 'shykot1198@gmail.com'],
            [
                'name' => 'Shykot Hasan',
                'phone' => '01620128405',
                'password' => Hash::make('12345678'),
                'monthly_income' => 32000,
                'cycle_start_date' => 7,
            ]
        );

        // 2. Default Categories
        $categories = [
            ['id' => 1, 'budget_type' => 'needs', 'name' => 'Rent', 'icon' => 'tag'],
            ['id' => 2, 'budget_type' => 'needs', 'name' => 'Utilities', 'icon' => 'tag'],
            ['id' => 3, 'budget_type' => 'needs', 'name' => 'Groceries', 'icon' => 'tag'],
            ['id' => 4, 'budget_type' => 'needs', 'name' => 'Transport', 'icon' => 'tag'],
            ['id' => 5, 'budget_type' => 'wants', 'name' => 'Dining Out', 'icon' => 'tag'],
            ['id' => 6, 'budget_type' => 'wants', 'name' => 'Entertainment', 'icon' => 'tag'],
            ['id' => 7, 'budget_type' => 'wants', 'name' => 'Shopping', 'icon' => 'tag'],
            ['id' => 8, 'budget_type' => 'savings', 'name' => 'Emergency Fund', 'icon' => 'tag'],
            ['id' => 9, 'budget_type' => 'savings', 'name' => 'Investment', 'icon' => 'tag'],
            ['id' => 10, 'budget_type' => 'savings', 'name' => 'DBBL Deposit', 'icon' => 'tag'],
            ['id' => 11, 'budget_type' => 'wants', 'name' => 'Fruits', 'icon' => 'tag'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['id' => $cat['id']],
                [
                    'user_id' => $user->id,
                    'budget_type' => $cat['budget_type'],
                    'name' => $cat['name'],
                    'icon' => $cat['icon'],
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]
            );
        }

        // 3. Default Expenses
        $expenses = [
            [
                'id' => 1,
                'category_id' => 1,
                'amount' => 7000.00,
                'expense_date' => '2026-05-09',
                'description' => null
            ],
            [
                'id' => 2,
                'category_id' => 2,
                'amount' => 2000.00,
                'expense_date' => '2026-05-09',
                'description' => 'Current Bill'
            ],
            [
                'id' => 3,
                'category_id' => 10,
                'amount' => 4800.00,
                'expense_date' => '2026-05-09',
                'description' => 'Deposite'
            ],
            [
                'id' => 4,
                'category_id' => 11,
                'amount' => 240.00,
                'expense_date' => '2026-05-09',
                'description' => 'kola ar peyara'
            ],
        ];

        foreach ($expenses as $exp) {
            Expense::updateOrCreate(
                ['id' => $exp['id']],
                [
                    'user_id' => $user->id,
                    'category_id' => $exp['category_id'],
                    'amount' => $exp['amount'],
                    'expense_date' => $exp['expense_date'],
                    'description' => $exp['description'],
                    'is_recurring' => false,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]
            );
        }
    }
}
