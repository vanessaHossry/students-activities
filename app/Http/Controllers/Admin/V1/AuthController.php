<?php

namespace App\Http\Controllers\Admin\V1;

use Exception;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\AuthRequest;
use App\Http\Controllers\Controller;
use App\Interfaces\AuthRepositoryInterface;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AuthController extends Controller
{
    use ApiResponse;
    private $authRepositoryInterface;
// == DECLARATION

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepositoryInterface = $authRepository;

        $this->middleware('auth:api', ['except' => ['login']]);
    }

//


// == GET

 
   /**
 * @OA\Get(
 *     path="/me",
 *     summary="Get self User",
 *     tags={"Auth"},
 *     security={{"bearerToken": {}}},
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
public function me()
{
    try{
        $user = $this->authRepositoryInterface->me();
        if(!isset($user)){
            return $this->errorResponse("failed", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->successResponse($user,Response::HTTP_OK);
    }
    catch (Exception $e) {
        return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    
}

// == EDIT

    // --- login
    /**
     
        * @OA\Post(
        * path="/login",
        * operationId="userSignUi",
        * tags={"Auth"},
        * summary="authorize user",
        *     @OA\RequestBody(
        *           required=true,
        *           description="Body request needed to add user object",
        *            @OA\MediaType(
        *            mediaType="application/json",
        *            @OA\Schema(
        *               required={"email", "password"},
        *               @OA\Property(property="email", description="email"),
        *               @OA\Property(property="password",description="password"),
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
     */
    public function login(AuthRequest $request)
    {
        try {
           $token =  $this->authRepositoryInterface->login($request);
           if(!isset($token)) 
             return $this->errorResponse('unauthorized', Response::HTTP_UNAUTHORIZED);
          
             return $this->successResponse($token)->withCookie(cookie('token', $token, time() + 3600));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    // --- sign up

    /**
     
     * @OA\Post(
     * path="/signup",
     * operationId="userSignUp",
     * tags={"Auth"},
     * summary="user signup",
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
     */
    public function store(AuthRequest $request)
    {
        try {

            $user = $this->authRepositoryInterface->store($request);

            if(!isset($user))
                return $this->errorResponse("Failed", Response::HTTP_INTERNAL_SERVER_ERROR);
            
            return $this->successResponse($user, Response::HTTP_OK);
            
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

// --- logout 
/**
 

    * @OA\Get(
    *     path="/logout",
    *     summary="log out user",
    *     tags={"Auth"},
    *     security={{ "bearerToken": {} }},
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

    public function logout()
    {
        try {

            $user = auth()->user();
            $userName = $user->first_name;
            auth()->logout();
            return $this->successResponse("Bye  $userName ! Successfully logged out");


        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

}