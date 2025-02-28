<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class ShiftService{
    
    public function getAuthUserActiveShift(): ?Shift{
        $authUser = Auth::user();
        return Shift::active($authUser->id)->first();
    }


    public function start(): string{
        $shift = $this->getAuthUserActiveShift();
        // Check older for unclosed shifts
        if($shift){
            return  "Older shift started at [ $shift->start ] was not closed and is now in use";
        }

        $shift = new Shift();
        $shift->user()->associate(Auth::user());
        $shift->save();
        return 'Shift started successfully at ' . Carbon::now()->toDateTimeString();
    }

    private function calculateRevenue(Shift $shift): float{
        $transactionsRev = $shift->transactions()->sum('final_price');
        $purchaseOrdersRev = $shift->purchaseOrders()->sum('total_price');
        return $transactionsRev + $purchaseOrdersRev;
    }

    public function close(Shift $shift ,float $safeMoney): string{

        if(!$shift){
            throw new InvalidArgumentException("User has no open shifts, please start a shift first", 1);
        }

        $totalRev = $this->calculateRevenue($shift);
        $difference = $safeMoney - $totalRev;

        $shift->total_revenue = $totalRev;
        $shift->difference = $difference;
        $shift->end = Carbon::now();
        $shift->save();

        $username = $shift->user->name;
        return "
        Shift closed
        User: $username
        Total: $totalRev
        Entered Amount: $safeMoney
        Difference: $difference
        ";
    }


}