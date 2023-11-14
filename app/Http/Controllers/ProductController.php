<?php

namespace App\Http\Controllers;

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
    public function getdata(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);
        $items = product::with(['category', 'image'])->skip(($page - 1) * $limit)->take($limit)->get();
        return response()->json($items);
    }
    public function getpro()
    {
        $products = home_appliance::with(['product.image', 'product.category'])->get();
        return response()->json(['products' => $products], 200);
    }
}
