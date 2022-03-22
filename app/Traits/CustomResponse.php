<?php

namespace App\Traits;

trait CustomResponse {

    /**
     * Return success response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendSuccessResponse($data, $statusCode = 200, $message = '')
    {
        $response = [];
        if(is_array($data) && (count($data) > 0)) {
            $response['data'] = $data;
        }
        
        if($message=='') {
            if($statusCode===200) {
                $message = 'request_successful';
            } else if($statusCode===201) {
                $message = 'creation_successful';
            }else if($statusCode===202) {
                $message = 'acceptance_successful';
            }else if($statusCode===204) {
                $message = 'deletion_successful';
            }
        }

        if($message!='') {
            $response['message'] = trans("messages.{$message}");
            $response['result'] = true;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendErrorResponse($message, $statusCode = 400, $errors=[])
    {

        $response = [
            'message' => trans("messages.{$message}")
        ];

        $response['result'] = false;

        if(is_array($message))
        {
            $response = $message;
        }

        if(count($errors) >0)
        {
            $response['errors'] = $errors;
        }
        return response()->json($response, $statusCode);
    }

}
