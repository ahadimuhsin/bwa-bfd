<?php

namespace App\Http\Controllers;

use Faker\Core\Number;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if(request()->ajax())
        {
            $query = Product::query();

            return DataTables::of($query)
            ->addColumn('aksi', function($item){
                return '
                <a href="'.route('dashboard.product.gallery.index', $item->slug).'"
                    class="inline-block border border-gray-700 bg-gray-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline"
                    style="background-color: #CFEC7D">
                    Gallery
                </a>
                <a href="'.route('dashboard.product.edit', $item->slug).'"
                    class="inline-block border border-gray-700 bg-gray-700 text-white rounded-md px-2 py-1 m-1 transition duration-500 ease select-none hover:bg-gray-800 focus:outline-none focus:shadow-outline"
                    style="background-color: #469beb">
                    Edit
                </a>
                <form class="inline-block" action="' . route('dashboard.product.destroy', $item->slug) . '" method="POST">
                    ' . method_field('delete') . csrf_field() . '
                    <button class="border border-red-500 bg-red-500 text-white rounded-md px-2 py-1 m-2 transition duration-500 ease select-none hover:bg-red-600 focus:outline-none focus:shadow-outline"
                    style="background-color: #D46670">
                        Hapus
                    </button>

                </form>';
            })
            ->editColumn('price', function($item){
                return number_format($item->price);
            })
            ->rawColumns(['aksi'])
            ->toJson();
        }
        return view('pages.dashboard.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('pages.dashboard.product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        //
        $data = $request->all();

        $data['slug'] = Str::slug($request->name, '-');
        $data['price'] = preg_replace('/(?:[.]|\,00)/', '$1', $request->input('price'));

        Product::create($data);

        return redirect()->route('dashboard.product.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
        return view('pages.dashboard.product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        //
        // $product = Product::findOrFail($product->id);

        $data = $request->all();

        $data['slug'] = Str::slug($request->name, '-');
        $data['price'] = preg_replace('/(?:[.]|\,00)/', '$1', $request->input('price'));

        $product->update($data);

        return redirect()->route('dashboard.product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
        // $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('dashboard.product.index');
    }
}
