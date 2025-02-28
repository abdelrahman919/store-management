<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Shift;
use Illuminate\Http\Request;
use App\Services\ShiftService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreShiftRequest;
use App\Http\Requests\UpdateShiftRequest;
use Illuminate\Http\Request as HttpRequest;


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
        // , 'pruchase_orders'
        // $shift = Shift::with('transactions', 'user')->paginate(10);
        return $this->success($shift->load('transactions', 'user', 'purchaseOrders'));
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

    // public function close(float $safeMoney){
    //     $this->shiftService->close($safeMoney );
    // }

    public function close(Request $req){
        $req->validate([
            'safeMoney'=>['required', 'numeric', 'min:0'],
        ]);
        $safeMoney =(float) $req['safeMoney'];
        $shift = $this->shiftService->getAuthUserActiveShift();
        return $this->shiftService->close($shift, $safeMoney);
    }
}
