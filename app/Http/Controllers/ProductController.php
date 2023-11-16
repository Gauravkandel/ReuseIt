<?php

namespace App\Http\Controllers;

use App\Models\Furniture;
use App\Models\HomeAppliance;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'image'])->get();
        return response()->json(['products' => $products], 200);
    }

    public function fetchAllData(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);

        // Validate $page and $limit as numeric values here

        $items = Product::with(['category', 'image'])->paginate($limit);
        return response()->json($items);
    }

    public function getIndivProduct($id)
    {
        try {
            $product = Product::with(['category', 'image'])->findOrFail($id);

            $category = $product->category->category_name;

            $data = $this->getProductData($category, $id);

            return response()->json(['data' => $data], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found'], 404);
        } catch (\Exception $e) {
            // Handle other exceptions if needed
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }


    private function getProductData($category, $id)
    {
        switch ($category) {
            case "Home Appliances":
                return HomeAppliance::with(['product', 'product.image', 'product.category'])->where('product_id', $id)->get();
            case "Furniture":
                return Furniture::with(['product', 'product.image', 'product.category'])->where('product_id', $id)->get();
            default:
                return null;
        }
    }
}
