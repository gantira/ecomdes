<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Product;
use App\Category;
use File;
use App\Jobs\ProductJob;
use App\Jobs\MarketplaceJob;
use Image;

class ProductController extends Controller
{

    public $path;
    public $dimensions;

    public function __construct()
    {
        $this->path = storage_path('app/public/products');
        $this->dimensions = ['245', '300', '500'];
    }

    public function index()
    {
        $product = Product::with(['category'])->orderBy('created_at', 'DESC');
        if (request()->q != '') {
            $product = $product->where('name', 'LIKE', '%' . request()->q . '%');
        }
        $product = $product->paginate(10);
        return view('products.index', compact('product'));
    }

    public function create()
    {
        $category = Category::orderBy('name', 'DESC')->get();
        return view('products.create', compact('category'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer',
            'weight' => 'required|integer',
            'satuan' => 'required',
            'image' => 'required|image|mimes:png,jpeg,jpg'
        ]);

        if (!File::isDirectory($this->path)) {
            File::makeDirectory($this->path);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            Image::make($file)->save($this->path . '/' . $fileName, 60);

            foreach ($this->dimensions as $row) {
                $canvas = Image::canvas($row, $row);
                $resizeImage  = Image::make($file)->resize($row, $row, function ($constraint) {
                    $constraint->aspectRatio();
                });

                if (!File::isDirectory($this->path . '/' . $row)) {
                    File::makeDirectory($this->path . '/' . $row);
                }

                $canvas->insert($resizeImage, 'center');
                $canvas->save($this->path . '/' . $row . '/' . $fileName);
            }

            $product = Product::create([
                'name' => $request->name,
                'slug' => $request->name,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'image' => $fileName,
                'price' => $request->price,
                'weight' => $request->weight,
                'status' => $request->status,
                'satuan' => $request->satuan
            ]);

            return redirect(route('product.index'))->with(['success' => 'Produk Baru Ditambahkan']);
        }
    }

    public function edit($id)
    {
        $product = Product::find($id);
        $category = Category::orderBy('name', 'DESC')->get();
        return view('products.edit', compact('product', 'category'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer',
            'weight' => 'required|integer',
            'satuan' => 'required',
            'image' => 'nullable|image|mimes:png,jpeg,jpg'
        ]);

        if (!File::isDirectory($this->path)) {
            File::makeDirectory($this->path);
        }

        $product = Product::find($id);
        $fileName = $product->image;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            Image::make($file)->save($this->path . '/' . $fileName, 60);

            File::delete(storage_path('app/public/products/' . $product->image));

            foreach ($this->dimensions as $row) {
                $canvas = Image::canvas($row, $row);
                $resizeImage  = Image::make($file)->resize($row, $row, function ($constraint) {
                    $constraint->aspectRatio();
                });

                if (!File::isDirectory($this->path . '/' . $row)) {
                    File::makeDirectory($this->path . '/' . $row);
                }

                File::delete($this->path . '/' . $row . '/' . $product->image);

                $canvas->insert($resizeImage, 'center');
                $canvas->save($this->path . '/' . $row . '/' . $fileName);
            }
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'weight' => $request->weight,
            'satuan' => $request->satuan,
            'image' => $fileName
        ]);

        return redirect(route('product.index'))->with(['success' => 'Data Produk Diperbaharui']);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        File::delete(storage_path('app/public/products/' . $product->image));
        foreach ($this->dimensions as $row) {
            File::delete($this->path . '/' . $row . '/' . $product->image);
        }

        $product->delete();

        return redirect(route('product.index'))->with(['success' => 'Produk Sudah Dihapus']);
    }

    public function massUploadForm()
    {
        $category = Category::orderBy('name', 'DESC')->get();
        return view('products.bulk', compact('category'));
    }

    public function massUpload(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required|exists:categories,id',
            'file' => 'required|mimes:xlsx'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '-product.' . $file->getClientOriginalExtension();
            $file->storeAs('public/uploads', $filename);
            // Image::make($file)->save($this->path . '/' . $fileName, 60);

            ProductJob::dispatch($request->category_id, $filename);
            return redirect()->back()->with(['success' => 'Upload Produk Dijadwalkan']);
        }
    }

    public function uploadViaMarketplace(Request $request)
    {
        $this->validate($request, [
            'marketplace' => 'required|string',
            'username' => 'required|string'
        ]);

        MarketplaceJob::dispatch($request->username, 10);
        return redirect()->back()->with(['success' => 'Produk Dalam Antrian']);
    }
}
