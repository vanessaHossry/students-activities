<?php

namespace App\Http\Controllers\Client\V1;

use Exception;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\AuthRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class UserAuthController extends Controller
{
    use ApiResponse, Docs;

    public function __Construct(){
        $this->middleware('auth:api',['except'=>['login']]);
    }
    

    // --- login
     /**
     * @OA\Post(
     * path="/client/v1/login",
     * tags={"User Auth"},
     * security={{ "APIKey": {} }},
     * summary="authenticate user",
     * 
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
    public function login(AuthRequest $request){
       try{
        $credentials = request(['email', 'password']);
        $token = auth()->attempt($credentials);
        if(!isset($token))
            return $this->errorResponse('unauthenticated' , Response::HTTP_UNAUTHORIZED);
        
            return $this->successResponse($token)->withCookie(cookie('token',$token,(Auth::factory()->getTTL()) , null, null, true, true, false, 'none'));

       } 
       catch(Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
       }
    }

     // --- logout 
    /**
     * @OA\Get(
     * 
     * path="/client/v1/logout",
     * tags={"User Auth"},
     * security={{"APIKey": {}}},
     * summary="log out user",
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
    public function logout(){
        try
        {
            $user=auth()->user();
            $userName= $user->first_name;
            $cookie = Cookie::forget('token');
            auth()->logout();
            return $this->successResponse("Bye  $userName ! Successfully logged out")->withCookie($cookie);

        }
        catch(Exception $e){
            $this->errorResponse($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
