<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\TagRequest;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct(Tag $tag) {
        $this->tag = $tag;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tag = $this->tag->get();
        return response()->json($tag, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TagRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagRequest $request)
    {
        $tag = $this->tag->create($request->all());
        return response()->json($tag, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = $this->tag->find($id);
        if (!$tag) {
            return response()->json(['erro'=>'Tag de id '.$id.' não existe.'], 404);
        }
        return response()->json($tag, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TagRequest  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TagRequest $request, $id)
    {
        $tag = $this->tag->find($id);
        if (!$tag) {
            return response()->json(['erro'=>'Tag de id '.$id.' não existe.'], 404);
        }

        $tag->fill($request->all());
        $tag->save();
        return response()->json($tag, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = $this->tag->find($id);
        if (!$tag) {
            return response()->json(['erro'=>'Tag de id '.$id.' não existe.'], 404);
        }
        $tag->delete();
        return response()->json($tag, 200);
    }
}
