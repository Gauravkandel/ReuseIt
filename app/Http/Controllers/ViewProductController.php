<?php

namespace App\Http\Controllers;

use App\Models\antique;
use App\Models\book;
use App\Models\clothing;
use App\Models\Furniture;
use App\Models\HomeAppliance;
use App\Models\music;
use App\Models\Product;
use App\Models\sport;
use App\Models\toy;
use App\Models\vehicle;
use Illuminate\Http\Request;

class ViewProductController extends Controller
{
    public function fetchAllData(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);
        $items = product::with(['category', 'image'])->skip(($page - 1) * $limit)->take($limit)->get();
        //pagination starts 1 to 10 and so on
        return response()->json($items);
    }

    public function getIndivProduct($id)
    {
        try {
            $product = Product::with(['category', 'image'])->findOrFail($id);

            $category = $product->category->category_name;

            $data = $this->getProductData($category, $id); //sending data to function getproductData

            return response()->json(['data' => $data], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found'], 404);
        } catch (\Exception $e) {
            // Handling exceptions 
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
            case "Clothing and Accessories":
                return clothing::with(['product', 'product.image', 'product.category'])->where('product_id', $id)->get();
            case "Sports and Fitness":
                return sport::with(['product', 'product.image', 'product.category'])->where('product_id', $id)->get();
            case "Books and Media":
                return book::with(['product', 'product.image', 'product.category'])->where('product_id', $id)->get();
            case "Antiques and Collectibles":
                return antique::with(['product', 'product.image', 'product.category'])->where('product_id', $id)->get();
            case "Vehicles":
                return vehicle::with(['product', 'product.image', 'product.category'])->where('product_id', $id)->get();
            case "Toys and Games":
                return toy::with(['product', 'product.image', 'product.category'])->where('product_id', $id)->get();
            case "Musical Instruments":
                return music::with(['product', 'product.image', 'product.category'])->where('product_id', $id)->get();
            default:
                return null;
        }
    }
}
