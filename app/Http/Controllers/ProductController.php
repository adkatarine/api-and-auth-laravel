<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tag;
use App\Models\ProductTag;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(Product $product) {
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = $this->product->get();
        return response()->json($product, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->product->rules(), $this->product->feedback());

        if($request->file->extension() == 'json') {
            $json = file_get_contents($request->file('file'));
            $data = json_decode($json);
            foreach ($data->products as $value) {

                $product = $this->product->create([
                    'id' => $value->id,
                    'name' => $value->name,
                ]);
            }
        } else {
            $xml = simplexml_load_file($request->file('file'));
            foreach ($xml as $value) {
                $product = $this->product->create([
                    'id' => $value->id,
                    'name' => $value->name,
                ]);
            }
        }


        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->product->find($id);
        if (!$product) {
            return response()->json(['erro'=>'Produto de id '.$id.' não existe.'], 404);
        }
        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate($this->product->rules(), $this->product->feedback());

        $product = $this->product->find($id);
        if (!$product) {
            return response()->json(['erro'=>'Produto de id '.$id.' não existe.'], 404);
        }

        $product->fill($request->all());
        $product->save();
        return response()->json($product, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = $this->product->find($id);
        if (!$product) {
            return response()->json(['erro'=>'Produto de id '.$id.' não existe.'], 404);
        }
        $product->delete();
        return response()->json($product, 200);
    }
}
