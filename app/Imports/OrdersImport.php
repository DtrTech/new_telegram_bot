<?php

namespace App\Imports;

use App\Http\Controllers\OrderController;
use App\Models\Order;
use App\Models\Bank;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrdersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $country = Country::where('country_code', Session::get('country'))->first();

        $bank = Bank::where('bank_code', $row['bank'])->first();

        if ($bank) {
            $OrderController = new OrderController;
            // Generate order data
            $order = new Order([
                'card_holder_name'      => $row['name'],
                'bank_account_number'   => $row['bank_account_number'],
                'total_amount'          => $row['amount'],
                'country_id'            => $country->id ?? null,
                'order_no'              => $OrderController->generateOrderNo(4),
                'order_type'            => 4, // pay on behalf
                'created_by'            => Auth::user()->id,
                'status'                => '1', // Submitted
            ]);

            $order->save();
            $order->banks()->sync([$bank->id]);
        }

        return $order;
    }
}
