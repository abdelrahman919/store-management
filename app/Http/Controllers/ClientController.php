<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Dotenv\Exception\ValidationException;
use Illuminate\Validation\ValidationException as ValidationValidationException;

class ClientController extends BaseController
{
    

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::paginate(10);
        return $this->success($clients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $validated = $request->validated();
        $client = Client::create($validated);
        return $this->created($client);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return $this->success($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $validated = $request->validated();
        $client->update($validated);
        return $this->success($client);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();
        
    }

    public function allowCredit(Client $client) {
        $client->update(['credit_allowed'=>true]);
        return $this->success();
    }

    public function updateCreditLimit(Client $client, $limit) {
        if (!is_numeric($limit)) {
            throw  ValidationValidationException::withMessages([
                'limit'=>'Credit limit must be a valid number'
            ]);
        }

        $client->update(['credit_limit'=> $limit]);
        return $this->success($client);

    }

}
