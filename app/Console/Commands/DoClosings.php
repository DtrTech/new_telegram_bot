<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\InputOption;
use Carbon\Carbon;
use Mail;
use App\Models\User;
use App\Models\BankAccount;
use App\Models\PackageInvoice;
use App\Models\BankClosing;
use App\Models\UserClosing;
use App\Models\PackageInvoiceClosing;
use App\Models\DailyReport;

class DoClosings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Cron';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $yesterday = Carbon::yesterday()->format('Y-m-d');

        if (DailyReport::where('daily_date', $yesterday)->exists()) {
            return;
        }
        
        $user = User::where('role_id', 3)->get(['id', 'wallet']);
        $bank_account = BankAccount::all(['id', 'amount']);
        $package_invoices = PackageInvoice::where('is_paid',0)->get();
        // $yesterday = Carbon::now()->format('Y-m-d');

        $userClosings = [];
        foreach ($user as $u) {
            $userClosings[] = [
                'user_id' => $u->id,
                'closing_date' => $yesterday,
                'amount' => $u->wallet,
                'created_at' => Carbon::now(),
            ];
        }

        $bankClosings = [];
        foreach ($bank_account as $bank) {
            $bankClosings[] = [
                'bank_account_id' => $bank->id,
                'closing_date' => $yesterday,
                'amount' => $bank->amount,
                'created_at' => Carbon::now(),
            ];
        }

        $packageInvoiceClosing = [];
        foreach ($package_invoices as $package_invoice) {
            $packageInvoiceClosing[] = [
                'package_invoice_id' => $package_invoice->id,
                'closing_date' => $yesterday,
                'amount' => $package_invoice->balance,
                'created_at' => Carbon::now(),
            ];
        }

        UserClosing::insert($userClosings);
        BankClosing::insert($bankClosings);
        PackageInvoiceClosing::insert($packageInvoiceClosing);

        $user_closing_total = UserClosing::where('closing_date',$yesterday)->sum('amount');
        $bank_closing_total = BankClosing::where('closing_date',$yesterday)->sum('amount');
        $package_invoice_closing_total = PackageInvoiceClosing::where('closing_date',$yesterday)->sum('amount');

        $total = $bank_closing_total - $user_closing_total;
        DailyReport::create([
            'daily_date'=>$yesterday,
            'bank_total'=>$bank_closing_total,
            'customer_total'=>$user_closing_total,
            'package_havent_pay'=>$package_invoice_closing_total,
            'total'=>$total,
        ]);
    }
}
