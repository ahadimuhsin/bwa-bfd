<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductGalleryRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductGallery;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProductGalleryController extends Controller
{
    //
    public function index(Product $product)
    {
        if(request()->ajax())
        {
            $query = ProductGallery::where('product_id', $product->id)->get();

            return DataTables::of($query)
            ->addColumn('aksi', function($item){
                return '
                <form class="inline-block" action="' . route('dashboard.gallery.destroy', $item->id) . '" method="POST">
                    ' . method_field('delete') . csrf_field() . '
                    <button class="border border-red-500 bg-red-500 text-white rounded-md px-2 py-1 m-2 transition duration-500 ease select-none hover:bg-red-600 focus:outline-none focus:shadow-outline"
                    style="background-color: #D46670">
                        Hapus
                    </button>

                </form>';
            })
            ->editColumn('url', function($item){
                return '<img src="'.Storage::url($item->url).'" style="max-width: 150pxW">';
            })
            ->editColumn('is_featured', function($item){
                return $item->is_featured ? 'Yes' : 'No';
            })
            ->rawColumns(['aksi', 'url'])
            ->toJson();
        }
        return view('pages.dashboard.product-gallery.index', compact('product'));
    }

    public function create(Product $product)
    {
        return view('pages.dashboard.product-gallery.create', compact('product'));
    }

    public function store(ProductGalleryRequest $request, Product $product)
    {

        $files = $request->file('url');
        if($request->hasFile('url'))
        {
            foreach($files as $file)
            {
                $path = $file->store('public/gallery');

                ProductGallery::create([
                    'product_id' => $product->id,
                    'url' => $path
                ]);
            }
            return redirect()->route('dashboard.product.gallery.index', $product->slug);
        }
        return view('pages.dashboard.product-gallery.create', compact('product'));
    }

    public function destroy(ProductGallery $gallery)
    {
        //
        // $product = Product::findOrFail($id);
        $slug = Product::where('id', $gallery->product_id)->pluck('slug')->first();
        // dd($slug);
        $gallery->delete();
        Storage::delete($gallery->url);

        return redirect()->route('dashboard.product.gallery.index', $slug);
    }
}
