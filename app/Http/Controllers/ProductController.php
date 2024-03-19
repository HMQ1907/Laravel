<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller

{

    protected $path = "images/products" ;

    public function index(Request $request)
    {
        try {
            $query = Product::select('product_id', 'product_name', 'product_image', 'product_price', 'is_active', 'description');

            if ($request->filled('product_name')) {
                $query->where('product_name', 'like', '%' . $request->input('product_name') . '%');
            }

            if ($request->filled('product_status')) {
                $query->where('is_active', $request->input('product_status'));
            }

            if ($request->filled('min_price') && is_numeric($request->input('min_price'))) {
                $query->where('product_price', '>=', $request->input('min_price'));
            }

            if ($request->filled('max_price') && is_numeric($request->input('max_price')) && $request->input('max_price') != 0) {
                $query->where('product_price', '<=', $request->input('max_price'));
            }

            $products = $query->paginate(20);

            if ($request->ajax()) {
                if ($products->isEmpty()) {
                    return response()->json(['error' => 'Không tìm thấy sản phẩm phù hợp.']);
                }
                $view = view('product.table', ['products' => $products])->render();
                $paginationLinks = $products->links()->toHtml();
                return response()->json(['html' => $view, 'pagination_links' => $paginationLinks]);
            }

            return view('product.index', ['products' => $products]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function add()
    {
        $title = 'Thêm sản phẩm';
        return view('product.add_edit', ['title' => $title]);
    }
    public function create(ProductRequest $request)
    {
        try {
            
            if (!file_exists(public_path($this->path))) {
                mkdir(public_path($this->path), 0777, true);
            }

            $product = new Product();
            $product->product_name = $request->product_name;
            $product->description = $request->product_desc;
            $product->product_price = $request->product_price;
            $product->is_active = $request->is_active;
            $product->product_image = null;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = $this->path . '/' . time() . '_' . uniqid() . '.' . $extension;
                $file->move(public_path($this->path), $filename);
                $product->product_image = $filename;
            }

            $product->save();
            return response()->json(['message' => 'Thêm sản phẩm thành công'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getProduct($id)
    {
        try {
            $title = 'Chỉnh sửa sản phẩm';
            $product = Product::select('product_id', 'product_name', 'product_image', 'product_price', 'is_active', 'description')->find($id);
            return view('product.add_edit', compact('product', 'title'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $messages = [
                'product_name.required' => 'Tên sản phẩm không được để trống',
                'product_name.min' => 'Tên sản phẩm không được ít hơn 5 kí tự',
                'product_name.is_active' => 'Trạng thái không được để trống',
                'product_price.numeric' => 'Nhập giá trị số',
            ];

            $validator = Validator::make(
                $request->all(),
                [
                    'product_name' => 'required|string|max:255|min:5',
                    'is_active' => 'required',
                    'product_price' => 'required|numeric',
                ],
                $messages,
            );

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            $product = Product::find($id);
            if (!$product) {
                return response()->json(['error' => 'Sản phẩm không tồn tại'], 404);
            }

            if ($request->hasFile('image')) {

                if ($product->product_image) {
                    $oldImagePath = public_path($product->product_image);

                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }

                }

                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = 'images/products/' . time() . '_' . uniqid() . '.' . $extension;
                $file->move(public_path('images/products'), $filename);
                $product->product_image = $filename;
            }

            $product->product_name = $request->product_name;
            $product->description = $request->product_desc;
            $product->product_price = $request->product_price;
            $product->is_active = $request->is_active;

            $product->save();

            return response()->json(['message' => 'Cập nhật sản phẩm thành công'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return response()->json(['error' => 'Sản phẩm không tồn tại'], 404);
            }
            $product->delete();
            return response()->json(['message' => 'Xóa sản phẩm thành công']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
