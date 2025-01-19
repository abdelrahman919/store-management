<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Code;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Http\FormRequest;
use PhpParser\Node\Stmt\Foreach_;

class ItemController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with('codes')->paginate(10);
        return $this->success($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        $validated = $request->validated();
        $item = new Item();


        DB::transaction(function () use ($validated, &$item) {
            
            $item = Item::create($validated['data']);
            
            if(isset($validated['relations']['codes'])){
                $codeValues =  $validated['relations']['codes'];
                foreach ($codeValues as $value) {
                    $item->Codes()->create(['value'=>$value]);
                } 
            }
        });
        return $this->created($item->load('codes'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        return $this->success($item->load('codes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $validated = $request->validated();
        $item->update($validated['data']);

        if(isset($validated['relations']['codes'])){
            $codeValues =  $validated['relations']['codes'];
            foreach ($codeValues as $value) {
                $item->Codes()->create(['value'=>$value]);
            } 
        }
        return $this->success($item->load('codes'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return $this->success();
    }


    public function destroyCode(Code $code){
        $code->delete();
        return $this->success();
    }


}

