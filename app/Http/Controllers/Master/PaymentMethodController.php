<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentMethodRequest;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PaymentMethodController extends Controller
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function index()
    {
        return view('master.payment_method.index');
    }

    public function data()
    {
        $code = 'JP';
        $data = PaymentMethod::where('company_code', $code)->get();

        return DataTables::of($data)
            ->make(true);
    }

    public function store(PaymentMethodRequest $request)
    {
        DB::beginTransaction();

        try {
            $payment                     = new PaymentMethod();
            $payment->company_code       = env('ID');
            $payment->name               = $request->name;
            $payment->value              = $request->value;
            $payment->status             = $request->status;
            $payment->save();

            DB::commit();

            return response()->json([
                'data' => $payment
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e
            ], 500);
        }
    }
}
