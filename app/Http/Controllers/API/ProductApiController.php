<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProductApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit');

        $products = Products::paginate($limit);

        return response()->json([
            'data' => $products -> items(),
            'total_page' => $products -> lastPage(),
            'message' => 'Data Retrieved Succesfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['web_id'] =Str::uuid()->toString();
        Log::info(print_r($input, true));
        return response()-> json(['data' => Products::create($input)], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info("id = ".$id);
        $product = DB::table('products')
                        ->where('web_id', $id)
                        ->get();

        Log::debug("Product = ".$product);
        return response()->json([
            "data" => $product,
            "message" => "Data Retrieved Succesfully"
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Products::where('web_id', $id);
        $updated = [];

        if (!empty($request->input('product_title'))) 
        { 
            $updated['product_title'] = $request->input('product_title');
        }

        if (!empty($request->input('price')))
        {
            $updated["price"] = $request->input('price');
        }
        if (!empty($request->input("rating"))) 
        { 
            $updated['rating'] = $request->input("rating");
        }

        Log::info("Update Data".serialize($updated));
        $product->update($updated);

        return response()->json([], 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy($webId)
    {
        Log::info("web_id = " . $webId);
        $product = Products::where('web_id', $webId);

        $updated = [];
        $updated['is_deleted'] = true;

        $product->update($updated);

        return response()->json([], 204);
    }
}
