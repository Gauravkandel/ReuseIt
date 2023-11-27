<?php

namespace App\Http\Controllers;

use App\Models\category;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function getRecommendation(Request $request)
    {
        $cat_id = $request->id;
        $category_name = category::find($cat_id);
    }
}
