<?php

namespace App\Http\Controllers\Client\V1;

use Exception;
use App\Models\User;
use App\Traits\utilities;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Interfaces\UserInterface;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Client\V1\UserRequest;

class UserController extends Controller
{
    use ApiResponse, utilities, Docs;
    private $userRepository;
    public function __construct(UserInterface $userRepository)
    {
        $this->middleware('auth.apikey');
        $this->middleware('auth:api',["except" => ["signUp", 'requestReset', 'resetPassword']]);
        $this->userRepository = $userRepository;

        $this->middleware('permission:user.read', ['only' => ['getSelf']]);
       
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

    // --- Sign Up

    /**
     
     * @OA\Post(
     * path="/client/v1/signUp",
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
     *               @OA\Property(property="role_slug",description="role slug"),
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
public function signUp(UserRequest $request){
    try {
        $user = $this->userRepository->store($request);
        return $this->successResponse($user);
    }
    catch(Exception $e){
        return $this->errorResponse($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

    // == Forgot Password
    /**
     
     * @OA\Post(
     * path="/client/v1/forgotPassword",
     * operationId="request",
     * tags={"User"},
     * security={{ "APIKey": {} }},
     *     @OA\RequestBody(
     *           required=true,
     *            @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *               @OA\Property(property="email", description="email"),
     *              
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
 
public function forgotPassword(UserRequest $request){
        try {
            //  $user = $this->userRepository->getUserByEmail($request->email);       //NOT USEFUL HERE
            $token = DB::table('password_reset_tokens')->where('email', $request->email)->first('token');
            // option 1: return already existing token 
            //    if($token){
            //     return $this->successResponse($token);
            //    }

            //option 2: update and regenerate token
            if ($token) {
                $token = $this->generateToken('password_reset_tokens');
                DB::table('password_reset_tokens')->where('email', $request->email)->update(['token' => $token, 'created_at' => now()]);
                return $this->successResponse($token);
            }

            $token = $this->generateToken('password_reset_tokens');

            DB::table('password_reset_tokens')->insert([
                "email" => $request->email,
                "token" => $token,
                "created_at" => now()
            ]);
            // DB::insert('insert into password_reset_tokens (email,token,created_at) values (?, ?, ?)', [$request->email, $token]);
            return $this->successResponse($token);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }


    }

    // == Reset Password

    /**
     
     * @OA\Post(
     * path="/client/v1/resetPassword",
     * operationId="reset",
     * tags={"User"},
     * security={{ "APIKey": {} }},
     *     @OA\RequestBody(
     *           required=true,
     *            @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *               @OA\Property(property="token", description="token"),
     *               @OA\Property(property="email", description="email"),
     *               @OA\Property(property="password", description="password"),
     *               @OA\Property(property="password_confirmation", description="password confirmation"),
     *              
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

public function resetPassword(UserRequest $request){
        try {
            // $user = $this->userRepository->getUserByEmail($request->email);
            // if(isset($user)){ 
            $results = DB::table('password_reset_tokens')->where('email', $request->email)
                ->where('token', $request->token)->first();
            if ($results) {
                User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
                //$user->password = Hash::make($request->password);
                // $user->save();
                return $this->successResponse(__("passwords.reset"));
            }
            return $this->errorResponse("token not found", Response::HTTP_NOT_FOUND);

            // }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


}
