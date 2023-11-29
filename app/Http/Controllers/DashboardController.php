<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function myProducts()
    {
        $user = auth()->user();
        $products = product::where('user_id', $user->id)->get();
        return response()->json(['MyProducts' => $products], 200);
    }
    public function deleteAds($id)
    {
        $products = product::find($id);
        $products->delete();
        return response()->json(['Message' => 'Deleted Successfully']);
    }
}
