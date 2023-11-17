<?php

namespace App\Http\Controllers;

use App\Http\Requests\ElectronicsRequest;
use App\Http\Requests\FurnitureRequest;
use App\Http\Requests\HomeApplianceRequest;
use App\Models\Electronic;
use App\Models\furniture;
use App\Models\HomeAppliance;
use App\Models\Product;
use App\Models\Product_image;
use Illuminate\Support\Facades\DB;

class SellProductController extends Controller
{
    public function HomeAppliances(HomeApplianceRequest $request)
    {
        return $this->insertProduct($request, HomeAppliance::class, ['type_of_appliance', 'brand', 'model', 'capacity', 'features', 'condition', 'warranty_information']);
    }
    public function Electronics(ElectronicsRequest $request)
    {
        return $this->insertProduct($request, electronic::class, ['type_of_electronic', 'brand', 'model', 'capacity', 'condition', 'warranty_information']);
    }
    public function Furnitures(FurnitureRequest $request)
    {
        return $this->insertProduct($request, furniture::class, ['type_of_furniture', 'material', 'dimensions', 'color', 'style', 'condition', 'assembly_required']);
    }













    private function insertProduct($request, $model, $dataKeys)
    {
        $productData = $request->validated();
        DB::beginTransaction();

        try {
            // Insert into products table
            $product = Product::create($productData);

            // Insert into specific table (home_appliances or electronics or any other categoric fields)
            $specificData = $request->only($dataKeys);
            $specificData['product_id'] = $product->id;
            $specificModel = $model::create($specificData);

            // Store the uploaded image paths
            if ($request->has('image_urls')) {
                foreach ($request->file('image_urls') as $image) {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('images'), $imageName);
                    $productImage = new product_image([
                        'product_id' => $product->id,
                        'image_url' => $imageName,
                    ]);
                    $productImage->save();
                }
            }

            DB::commit();
            return response()->json(['success' => 'successful'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to insert data.'], 500);
        }
    }
}
