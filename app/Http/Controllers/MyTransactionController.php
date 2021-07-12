<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Yajra\DataTables\Facades\DataTables;

class MyTransactionController extends Controller
{
    //
    public function index()
    {
        if(request()->ajax())
        {
            $query = Transaction::with('user')->where('user_id', auth()->user()->id);

            return DataTables::of($query)
            ->addColumn('aksi', function($item){
                return '
                <a href="'.route('dashboard.my-transaction.show', $item->id).'"
                    class="inline-block border border-gray-700 bg-gray-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline"
                    style="background-color: #CFEC7D">
                    Show
                </a>
                ';
            })
            ->editColumn('total_price', function($item){
                return number_format($item->total_price);
            })
            ->rawColumns(['aksi'])
            ->toJson();
        }
        return view('pages.dashboard.transaction.index');
    }

    public function show(Transaction $myTransaction)
    {
        if(request()->ajax())
        {
            $query = TransactionItem::with('product')
            ->where('transaction_id', $myTransaction->id);
            // dd($query);
            return DataTables::of($query)
            ->editColumn('product.price', function($item){
                return number_format($item->product->price);
            })
            ->rawColumns(['aksi'])
            ->toJson();
        }
        return view('pages.dashboard.transaction.show', [
            'transaction' => $myTransaction
        ]);
    }
}
