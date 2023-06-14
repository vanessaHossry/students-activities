<?php

namespace App\Http\Controllers\Admin\V1;

use Exception;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Interfaces\UserInterface;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\V1\Docs;


class AdminController extends Controller
{
    use ApiResponse, Docs;
    private $userRepository;
    public function __construct(UserInterface $userRepository)
    {
        $this->middleware('auth.apikey');
        
        $this->userRepository = $userRepository;
    }

    // == GET

 
   /**
    
* @OA\Get(
*     path="/admin/v1/getSelf",
*     summary="Get self User",
*     tags={"User"},
* security={{ "APIKey": {} }},
* 
*     @OA\Response(
*         response=200,
*         description="Successful operation",
*         @OA\JsonContent(
*          type="object",
*          @OA\Property(property="success", type="boolean", description="status" ),
*          @OA\Property(property="data", type="object", description="data" ),
*          @OA\Property(property="message", type="string", description="message" ),
*          ),
* 
*     ),
*     @OA\Response(
*         response=401,
*         description="Unauthorized"
*     )
* )
*/
    public function getSelf()
    {
        try {
            $user = $this->userRepository->getSelf();

            return $this->successResponse($user);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
