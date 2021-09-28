<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class BaseApiController extends Controller
{
    /**
     * Respond data
     * @param  array $data
     * @param  array  $headers
     * @return mixed
     */
    protected function respond($data, $headers = [])
    {
        return response()->json($data, Response::HTTP_OK, $headers);
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token,$user)
    {
        return response()->json([
            'code' => Response::HTTP_OK,
            'success' => true,
            'data' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('user')->factory()->getTTL() * 60
        ]);
    }


    /**
     * Respond data
     * @param  string $message
     * @param  string  $code
     * @return 
     */
    protected function responseMessage($message,$code)
    {
        
        return response()->json([
            'code' => $code,
            'message' => $message
        ], $code);
    }

    /**
     * Respond message with error
     *
     * @param  string  $description
     * @param  integer $code
     * @param  array   $messages
     * @return mixed
     */
    public function respondWithError($errors, $code = Response::HTTP_BAD_REQUEST)
    {
        $i = 0;
        $listError = [];
        foreach($errors as $key => $value)
        {
            $listError[$i]['type'] = $key;
            $listError[$i]['msg'] = $value;
            $i++;
        }
        $data = [
            'code' => $code,
            'success' => false,
            'errors' => $listError
        ];

        return response()->json($data,$code);
    }

    /**
     * Respond with success
     * @return mixed
     */
    public function respondWithSuccess( $code = 200 )
    {   
        return response()->json(["code"=> $code ,'success' => true]);
    }

    /**
     * Respond with data
     * @param  array $data
     * @return mixed
     */
    public function respondWithData($data = [])
    {
        $data = [
            'success' => true,
            'data' => $data,
            'code'=> Response::HTTP_OK
        ];

        return $this->respond($data);
    }


    /**
     * Respond with custom data
     * @param  string $data
     * @param  string $code
     * @param  string $success
     * @param  array $errors
     * @return mixed
     */
    public function reponseJson($data = [], $code = 200, $success = true, $errors = [])
    {
        $reponds = [
            'success' => $success,
            'code'    => $code,
        ];

        if($errors){
            $reponds['errors'] = $errors;
        }
        
        if($data){
            $reponds['data'] = $data;
        }

        return response()->json($reponds,$code);
    }
}