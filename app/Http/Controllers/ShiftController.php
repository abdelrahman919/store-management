<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Http\Requests\StoreShiftRequest;
use App\Http\Requests\UpdateShiftRequest;
use App\Services\ShiftService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ShiftController extends BaseController
{

    private ShiftService $shiftService;

    public function __construct(ShiftService $shiftService) {
        $this->shiftService = $shiftService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(Shift::with('user')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShiftRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Shift $shift)
    {
        $shift = Shift::with('transactions', 'pruchase_orders', 'user')->paginate(10);
        return $this->success($shift);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShiftRequest $request, Shift $shift)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shift $shift)
    {
        //
    }

    public function start() {
        $message = $this->shiftService->start();
        return $this->success(null, $message);
    }

    public function close(float $safeMoney){
        $this->shiftService->close($safeMoney );
    }

}
