<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tag;
use App\Models\ProductTag;
use App\Helper\FileAdapter;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function __construct(Product $product) {
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Cache::remember('list_products', 1440, function () {
            return new ProductCollection($this->product->with('product_tags.tag')->get());
        });
        if($request->has('filter')) {
            $filters = explode(';', $request->filter);
            foreach ($filters as $condition) {
                $value = explode(':', $condition);
                $products = $products->where($value[0], $value[1], $value[2]);
            }
        }
        return response()->json($products, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $products = FileAdapter::file($request->file('file'));

        foreach ($products as $value) {
            $product = $this->product->create([
                'id' => $value->id,
                'name' => $value->name,
            ]);

            foreach ($value->tags as $tagName) {
                $tag = Tag::where('name',$tagName)->first();
                if(!$tag) {
                    return response()->json(['erro'=>'Tag '.$tagName.' não está cadastrada.'], 404);
                }
                if(!ProductTag::where('product_id',$product->id)->where('tag_id',$tag->id)->first()){
                    ProductTag::create([
                        'product_id' => $product->id,
                        'tag_id' => $tag->id,
                    ]);
                }
            }
        }
        return response()->json("Produtos cadastrados com sucesso!", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = new ProductResource($this->product->with('product_tags.tag')->find($id));
        if (!$product) {
            return response()->json(['erro'=>'Produto de id '.$id.' não existe.'], 404);
        }
        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProductRequest  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
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
