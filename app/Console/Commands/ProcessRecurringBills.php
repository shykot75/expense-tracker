<?php

namespace App\Console\Commands;

use App\Models\RecurringBill;
use App\Models\Expense;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProcessRecurringBills extends Command
{
    protected $signature = 'app:process-recurring-bills';
    protected $description = 'Process due recurring bills and log them as expenses';

    public function handle()
    {
        $today = Carbon::today();
        
        $dueBills = RecurringBill::where('status', 'active')
            ->where('next_deduction_date', '<=', $today)
            ->get();

        if ($dueBills->isEmpty()) {
            $this->info('No due bills to process.');
            return;
        }

        $this->info('Processing ' . $dueBills->count() . ' bills...');

        foreach ($dueBills as $bill) {
            DB::transaction(function () use ($bill, $today) {
                // 1. Create Expense Entry
                Expense::create([
                    'user_id' => $bill->user_id,
                    'category_id' => $bill->category_id,
                    'amount' => $bill->amount,
                    'description' => '[Auto] ' . $bill->description,
                    'expense_date' => $bill->next_deduction_date,
                ]);

                // 2. Calculate Next Deduction Date
                $nextDate = match ($bill->frequency) {
                    'daily' => $bill->next_deduction_date->addDay(),
                    'weekly' => $bill->next_deduction_date->addWeek(),
                    'monthly' => $bill->next_deduction_date->addMonth(),
                    'yearly' => $bill->next_deduction_date->addYear(),
                    default => $bill->next_deduction_date->addMonth(),
                };

                // If the next date is still in the past (e.g. bill was missed for months),
                // we keep adding until it's in the future relative to the deduction date
                while ($nextDate->lte($today)) {
                    $nextDate = match ($bill->frequency) {
                        'daily' => $nextDate->addDay(),
                        'weekly' => $nextDate->addWeek(),
                        'monthly' => $nextDate->addMonth(),
                        'yearly' => $nextDate->addYear(),
                    };
                }

                $bill->update(['next_deduction_date' => $nextDate]);
            });

            $this->line('Processed: ' . $bill->description);
        }

        $this->info('Automation complete.');
    }
}
