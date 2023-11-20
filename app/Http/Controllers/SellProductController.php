<?php

namespace App\Http\Controllers;

use App\Http\Requests\AntiquesRequest;
use App\Http\Requests\BooksRequest;
use App\Http\Requests\ClothingRequest;
use App\Http\Requests\ElectronicsRequest;
use App\Http\Requests\FurnitureRequest;
use App\Http\Requests\HomeApplianceRequest;
use App\Http\Requests\MusicRequest;
use App\Http\Requests\SportsRequest;
use App\Http\Requests\ToysRequest;
use App\Http\Requests\VehiclesRequest;
use App\Models\antique;
use App\Models\book;
use App\Models\clothing;
use App\Models\Electronic;
use App\Models\furniture;
use App\Models\HomeAppliance;
use App\Models\music;
use App\Models\Product;
use App\Models\Product_image;
use App\Models\sport;
use App\Models\toy;
use App\Models\vehicle;
use Illuminate\Support\Facades\DB;

class SellProductController extends Controller
{
    public function Electronics(ElectronicsRequest $request)
    {
        return $this->insertProduct($request, electronic::class, ['type_of_electronic', 'brand', 'model', 'capacity', 'condition', 'warranty_information'], 1);
    }
    public function HomeAppliances(HomeApplianceRequest $request)
    {
        return $this->insertProduct($request, HomeAppliance::class, ['type_of_appliance', 'brand', 'model', 'capacity', 'features', 'condition', 'warranty_information'], 2);
    }
    public function Furnitures(FurnitureRequest $request)
    {
        return $this->insertProduct($request, furniture::class, ['type_of_furniture', 'material', 'dimensions', 'color', 'style', 'condition', 'assembly_required'], 3);
    }
    public function Clothing(ClothingRequest $request)
    {
        return $this->insertProduct($request, clothing::class, ['type_of_clothing_accessory', 'size', 'color', 'brand', 'material', 'condition', 'care_instructions'], 4);
    }
    public function Sports(SportsRequest $request)
    {
        return $this->insertProduct($request, sport::class, ['type_of_equipment', 'brand', 'condition', 'size_weight', 'features', 'suitable_sport_activity', 'warranty_information', 'usage_instructions'], 5);
    }
    public function Books(BooksRequest $request)
    {
        return $this->insertProduct($request, book::class, ['title', 'author_artist', 'genre', 'format', 'condition', 'edition', 'isbn_upc', 'warranty_information', 'description'], 6);
    }
    public function Antiques(AntiquesRequest $request)
    {
        return $this->insertProduct($request, antique::class, ['type_of_item', 'era_period', 'material', 'condition', 'provenance_location', 'rarity', 'historical_significance', 'certification'], 7);
    }
    public function Vehicles(VehiclesRequest $request)
    {
        return $this->insertProduct($request, vehicle::class, ['brand', 'model', 'year', 'mileage', 'condition', 'color', 'used_time', 'fuel_type', 'owner', 'transmission_type', 'vin'], 8);
    }
    public function Toys(ToysRequest $request)
    {
        return $this->insertProduct(
            $request,
            toy::class,
            ['type_of_toy_game', 'age_group', 'brand', 'condition', 'description', 'safety_information', 'assembly_required', 'recommended_use'],
            9
        );
    }
    public function Music(MusicRequest $request)
    {
        return $this->insertProduct(
            $request,
            music::class,
            ['type_of_toy_game', 'brand', 'age_group', 'material', 'accessories_included', 'play_characteristics'],
            10
        );
    }



    private function insertProduct($request, $model, $dataKeys, $category)
    {
        $productData = $request->validated();
        DB::beginTransaction();

        try {
            // Insert into products table
            $productData['category_id'] = $category;
            $product = Product::create($productData);

            // Insert into specific table (home_appliances or electronics or any other categoric fields)
            $specificData = $request->only($dataKeys);
            $specificData['product_id'] = $product->id;
            $specificModel = $model::create($specificData);

            // Store the uploaded image paths
            if ($request->has('image_urls')) {
                foreach ($request->file('image_urls') as $index => $image) {
                    $imageName = time() . $index . '_' . $image->getClientOriginalName();
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
