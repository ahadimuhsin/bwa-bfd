<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax())
        {
            $query = Transaction::query();

            return DataTables::of($query)
            ->addColumn('aksi', function($item){
                return '
                <a href="'.route('dashboard.transaction.show', $item->id).'"
                    class="inline-block border border-gray-700 bg-gray-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline"
                    style="background-color: #CFEC7D">
                    Show
                </a>
                <a href="'.route('dashboard.transaction.edit', $item->id).'"
                    class="inline-block border border-gray-700 bg-gray-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline"
                    style="background-color: #469beb">
                    Edit
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
        if(request()->ajax())
        {
            $query = TransactionItem::with('product')
            ->where('transaction_id', $transaction->id);
            // dd($query);
            return DataTables::of($query)
            ->editColumn('product.price', function($item){
                return number_format($item->product->price);
            })
            ->rawColumns(['aksi'])
            ->toJson();
        }
        return view('pages.dashboard.transaction.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
        return view('pages.dashboard.transaction.edit', compact('transaction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
        $this->validate($request, [
            'status' => 'required'
        ]);

        $data = Transaction::findOrFail($transaction->id);
        $data->status = $request->status;
        $data->save();

        return redirect()->route('dashboard.transaction.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
