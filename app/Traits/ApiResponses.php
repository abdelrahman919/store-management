<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{

    private function baseResponse($data = null, $message, $status){

        $respoonse = [
            'message' => $message
        ];
        
        if($data) {
            $respoonse['data'] = $data;
        }

        return response()->json($respoonse, $status);
    }


    protected function success($data = null, $message = 'Success', $status = 200){
        return $this->baseResponse($data, $message, $status);
    }

    protected function created($data = null, $modelname = null){
        return $this->baseResponse($data, $modelname . ' Created Successfully' , 201);
    }

    protected function deleted($data = null, $modelname = null){
        return $this->baseResponse($data, $modelname . ' Deleted Successfully' , 204);
    }


    protected function error($data = null, $message = 'error', $status = 400){
        return $this->baseResponse($data, $message, $status);
    }

}
