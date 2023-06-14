<?php

namespace App\Http\Controllers\Client\V1;

use Exception;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Interfaces\UserInterface;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;

class UserController extends Controller
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
*     path="/client/v1/getSelf",
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

  // --- sign up

    /**
     
     * @OA\Post(
     * path="/client/v1/store",
     * operationId="userSignUp",
     * tags={"User"},
     * summary="user signup",
     * security={{ "APIKey": {} }},
     *     @OA\RequestBody(
     *           required=true,
     *           description="Body request needed to add user object",
     *            @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *               @OA\Property(property="first_name", description="first name"),
     *               @OA\Property(property="last_name",description="last name"),
     *               @OA\Property(property="email", description="email"),
     *               @OA\Property(property="password",description="password"),
     *               @OA\Property(property="date_of_birth", description="date of birth", type="date"),
     *               @OA\Property(property="gender",description="gender"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response="200",
     *          description="Successful Operation",
     *          @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean", description="status" ),
     *          @OA\Property(property="data", type="object", description="data" ),
     *          @OA\Property(property="message", type="string", description="message" ),
     *          ),
     *        ),
     *       @OA\Response(
     *          response="422",
     *          description="Unprocessable Entity",
     *          @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean", description="status" ),
     *          @OA\Property(property="data",type="array",  @OA\Items( type="object"  ),description="data" ),
     *          @OA\Property(property="message", type="string", description="message" ),
     *          ),
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean", description="status" ),
     *          @OA\Property(property="data",type="array",  @OA\Items( type="object"  ),description="data" ),
     *          @OA\Property(property="message", type="string", description="message" ),
     *          ),
     *       ),
     * )
     *
      */
public function store(UserRequest $request){
    try {
        $user = $this->userRepository->store($request);
        return $this->successResponse($user);
    }
    catch(Exception $e){
        return $this->errorResponse($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}


}
