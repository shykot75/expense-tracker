<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Financial Report - {{ $date->format('F Y') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #1e293b; line-height: 1.5; padding: 20px; font-size: 12px; }
        .taka { font-family: 'font-awesome', sans-serif; color: #64748b; margin-right: 2px; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; }
        .header h1 { margin: 0; color: #0f172a; font-size: 24px; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0 0; color: #64748b; font-size: 12px; font-weight: bold; }
        
        .user-info { margin-bottom: 30px; font-size: 12px; }
        
        .summary-grid { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .summary-card { padding: 20px; background: #f8fafc; border-radius: 10px; border: 1px solid #f1f5f9; text-align: center; }
        .summary-label { display: block; font-size: 8px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 5px; }
        .summary-value { display: block; font-size: 18px; font-weight: bold; color: #0f172a; }
        
        .section-title { font-size: 10px; font-weight: bold; text-transform: uppercase; color: #94a3b8; margin-bottom: 15px; border-left: 4px solid #6366f1; padding-left: 10px; }
        
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .data-table th { text-align: left; font-size: 10px; color: #94a3b8; text-transform: uppercase; padding: 10px; border-bottom: 1px solid #f1f5f9; }
        .data-table td { padding: 12px 10px; font-size: 12px; border-bottom: 1px solid #f1f5f9; }
        
        .footer { text-align: center; margin-top: 50px; font-size: 10px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Monthly Statement</h1>
        <p>{{ $date->format('F Y') }}</p>
    </div>

    <div class="user-info">
        <strong>Client:</strong> {{ $user->name }}<br>
        <strong>Email:</strong> {{ $user->email }}<br>
        <strong>Generated:</strong> {{ now()->format('d M, Y H:i') }}
    </div>

    <table width="100%" style="margin-bottom: 40px;">
        <tr>
            <td width="32%">
                <div class="summary-card">
                    <span class="summary-label">Total Income</span>
                    <span class="summary-value">{{ $user->currency_symbol }}{{ number_format($totalIncome) }}</span>
                </div>
            </td>
            <td width="2%"></td>
            <td width="32%">
                <div class="summary-card">
                    <span class="summary-label">Total Expenses</span>
                    <span class="summary-value" style="color: #ef4444;">{{ $user->currency_symbol }}{{ number_format($totalExpense) }}</span>
                </div>
            </td>
            <td width="2%"></td>
            <td width="32%">
                <div class="summary-card">
                    <span class="summary-label">Net Savings</span>
                    <span class="summary-value" style="color: {{ $savings >= 0 ? '#10b981' : '#ef4444' }};">{{ $user->currency_symbol }}{{ number_format($savings) }}</span>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Spending by Category</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Category</th>
                <th style="text-align: right;">Amount</th>
                <th style="text-align: right;">% of Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categorySummary as $category => $amount)
                <tr>
                    <td>{{ $category }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ $user->currency_symbol }}{{ number_format($amount) }}</td>
                    <td style="text-align: right; color: #64748b;">{{ round(($amount / max(1, $totalExpense)) * 100) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Recent Transactions</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Category</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyExpenses->take(20) as $expense)
                <tr>
                    <td>{{ $expense->expense_date->format('d M') }}</td>
                    <td>{{ $expense->description }}</td>
                    <td>{{ $expense->category->name }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ $user->currency_symbol }}{{ number_format($expense->amount) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        This report was generated automatically by WislySpend.<br>
        &copy; {{ date('Y') }} Etar Financial Services.
    </div>
</body>
</html>
