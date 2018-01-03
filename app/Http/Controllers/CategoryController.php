<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
USE App\Category;

use Illuminate\Support\Str;
use App\Transformers\CategoryTransformer;
use Carbon\Carbon;
class CategoryController extends Controller
{
   public function index()
    {
            $categories = Category::where('status', '1')->get();

            return fractal()
            ->collection($categories)
            ->parseIncludes([])
            ->transformWith(new CategoryTransformer)
            ->toArray();
    }

   public function find($id)
    {
        $category = Category::find($id);

        if(count($category)){
            return fractal()
            ->item($category)
            ->parseIncludes(['jobs'])
            ->transformWith(new CategoryTransformer)
            ->toArray();
        }
        else{
            return response()->json([
                'data' => [
                    'status' => 'Job is not available or deleted!']
                ], 404);
        }
    }


}
