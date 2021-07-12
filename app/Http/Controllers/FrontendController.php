<?php

namespace App\Http\Controllers;

use Exception;
use Midtrans\Snap;
use App\Models\Cart;
use Midtrans\Config;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CheckoutRequest;

class FrontendController extends Controller
{
    //
    public function index(Request $request)
    {
        $products = Product::with('galleries')->latest()->get();
        // dd($products);
        return view('pages.frontend.index', compact('products'));
    }

    public function detail(Request $request, $slug)
    {
        $product = Product::with('galleries')->where('slug', $slug)->firstOrFail();
        $recommendations = Product::with('galleries')->inRandomOrder()->limit(4)->get();
        return view('pages.frontend.detail', compact('product', 'recommendations'));
    }

    public function addToCart(Request $request, $id)
    {
        Cart::create([
            'user_id' => auth()->user()->id,
            'product_id' => $id,
        ]);

        return redirect('cart');
    }

    public function removeFromCart(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();
        return redirect('cart');
    }

    public function cart(Request $request )
    {
        $carts = Cart::with(['product.galleries'])
        ->where('user_id', Auth::id())
        ->get();
        return view('pages.frontend.cart', compact('carts'));
    }

    public function success(Request $request )
    {
        return view('pages.frontend.success');
    }

    public function checkout(CheckoutRequest $request)
    {
        $data = $request->all();

        //Get Carts Data
        $carts = Cart::with('product')
        ->where('user_id', auth()->user()->id)
        ->get();

        //Add To Transaction data
        $data['user_id'] = auth()->user()->id;
        $data['total_price'] = $carts->sum('product.price');

        //create transaction
        $transaction = Transaction::create($data);

        //create transaction item
        foreach($carts as $cart)
        {
            $items[] = TransactionItem::create([
                'transaction_id' => $transaction->id,
                'user_id' => auth()->user()->id,
                'product_id' => $cart->product_id
            ]);
        }

        //delete cart after transaction
        Cart::where('user_id', auth()->user()->id)->delete();

        //midtrans configurations
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');
        //setup variable midtrans

        $params = [
                'transaction_details' => [
                    'order_id' => 'LUX-'.$transaction->id,
                    'gross_amount' => (int) $transaction->total_price
                ],
                'customer_details' => [
                    'first_name' => $transaction->name,
                    'email' => $transaction->email
                ],
                'enabled_payments' => ['gopay', 'bank_transfer'],
                'vtweb' => [],
            ];
        //payment
        try {
            // Get Snap Payment Page URL
            $paymentUrl = Snap::createTransaction($params)->redirect_url;
            $transaction->payment_url = $paymentUrl;
            $transaction->save();

            return redirect()->away($paymentUrl);
          }
          catch (Exception $e) {
            echo $e->getMessage();
          }
    }
}
