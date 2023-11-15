<?php

namespace App\Http\Controllers;

use App\Models\furniture;
use App\Models\home_appliance;
use App\Models\product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = product::with(['category', 'image'])->get();
        return response()->json(['products' => $products], 200);
    }
    public function fetchalldata(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);
        $items = product::with(['category', 'image'])->skip(($page - 1) * $limit)->take($limit)->get();
        return response()->json($items);
    }
    public function getIndivProduct($id)
    {
        $products = product::with(['category', 'image'])->find($id);
        $category = $products->category->category_name;
        if ($category == "Home Appliances") {
            $data = home_appliance::with(['product', 'product.image', 'product.category'])->where('product_id', $id)->get();
            return response()->json(['data' => $data ?? 0], 200);
        } else if ($category == "Furniture") {
            $data = furniture::with(['product', 'product.image', 'product.category'])->where('product_id', $id)->get();
            return response()->json(['data' => $data ?? 0], 200);
        }
    }
}
