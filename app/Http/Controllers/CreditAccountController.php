<?php

namespace App\Http\Controllers;

use App\Models\CreditAccount;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCreditAccountRequest;
use App\Http\Requests\UpdateCreditAccountRequest;

class CreditAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCreditAccountRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CreditAccount $creditAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCreditAccountRequest $request, CreditAccount $creditAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CreditAccount $creditAccount)
    {
        //
    }
}
