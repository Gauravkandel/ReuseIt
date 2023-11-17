<?php

namespace App\Http\Controllers;

use App\Http\Requests\HomeApplianceRequest;
use App\Models\HomeAppliance;
use App\Models\Product;
use App\Models\product_image;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SellProductController extends Controller
{
    public function HomeAppliances(HomeApplianceRequest $request)
    {
        try {
            // Insert into products table
            $productData = $request->validated();
            DB::beginTransaction();
            $product = Product::create($productData);

            // Insert into home_appliances table
            $homeApplianceData = $request->only(['type_of_appliance', 'brand', 'model', 'capacity', 'features', 'condition', 'warranty_information']);
            $homeApplianceData['product_id'] = $product->id; // Set the product_id
            $homeAppliance = HomeAppliance::create($homeApplianceData);

            // Store the uploaded image paths in the database and public/images directory
            $imagePaths = [];
            if ($request->has('image_urls')) {
                foreach ($request->file('image_urls') as $image) {
                    // Move image to public/images directory
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('images'), $imageName);
                    $productImage = new product_image([
                        'product_id' => $product->id,
                        'image_url' => $imageName, // Store the relative path
                    ]);
                    $productImage->save();

                    $imagePaths[] = 'images/' . $imageName;
                }
            }

            DB::commit();

            return response()->json(['success' => 'successful', 'image_paths' => $imagePaths], 200);
        } catch (\Exception $e) {
            // if error occurred, rollback the transaction
            DB::rollback();

            return response()->json(['error' => 'Failed to insert data.'], 500);
        }
    }
}
