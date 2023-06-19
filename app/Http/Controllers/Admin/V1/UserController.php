<?php

namespace App\Http\Controllers\Admin\V1;

use Exception;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Interfaces\UserInterface;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\V1\Docs;
use App\Http\Requests\Admin\V1\UserRequest;


class UserController extends Controller
{
    use ApiResponse, Docs;
    private $userRepository;
    public function __construct(UserInterface $userRepository)
    {
        $this->middleware('auth.apikey');
        $this->middleware('auth:api');
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

    /**
    
* @OA\Get(
*     path="/admin/v1/index",
*     summary="Get all Users",
*     tags={"User"},
*     security={{ "APIKey": {} }},
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
    public function index()
    {
        try
           {
            $users = $this->userRepository->index();
            return $this->successResponse($users);
           } 
           catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
           }

    }

      /**
     * @OA\Get(
     *      path="/admin/v1/show/{email}",
     *      operationId="getUserByEmail",
     *      tags={"User"},
     *      summary="Retrieve specific user",
     *       security={{ "APIKey": {} }},
     *      @OA\Parameter(
     *        name="email", in="path",required=true, @OA\Schema(type="string")
     *     ),
     *      @OA\Response(
     *          response="200",
     *          description="Successful Operation",
     *          @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean", description="status" ),
     *          @OA\Property(property="data", type="object", description="data" ),
     *          @OA\Property(property="message", type="string", description="message" ),
     *          ),
     *       ),
     *
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
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *
     *     )
     */

    public function show(UserRequest $request){
        try {   
                // $email = Route::current()->parameter('email');
                // $request->merge(['email' => $email]);
            $user = $this->userRepository->show($request);
            if(!$user)
            return $this->successResponse('user not found');
                
            return $this->successResponse($user);
            }
            catch(Exception $e){
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
    }

    // --- deleted users
    /**
        
    * @OA\Get(
    *     path="/admin/v1/getDeleted",
    *     summary="Get all Deleted Users",
    *     tags={"User"},
    *     security={{ "APIKey": {} }},
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

    public function getDeleted(){
        try{
            $users= $this->userRepository->getDeleted();
            return $this->successResponse($users);
        }
        catch(Exception $e){
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

     // --- create user

    /**
     
     * @OA\Post(
     * path="/admin/v1/store",
     * operationId="createUser",
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
    public function store(UserRequest $request)
    {
        try{
            $user = $this->userRepository->store($request);
            return $this->successResponse($user);
        }
        catch(Exception $e)
        {
            return $this->errorResponse($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // --- delete a user
    /**
     * @OA\Delete(
     *      path="/admin/v1/destroy/{email}",
     *      operationId="deleteUserByEmail",
     *      tags={"User"},
     *      summary="Delete a specific user",
     *      security={{ "APIKey": {} }},
     *      @OA\Parameter(
     *        name="email", in="path",required=true, @OA\Schema(type="string")
     *     ),
     *      @OA\Response(
     *          response="200",
     *          description="Successful Operation",
     *          @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="success", type="boolean", description="status" ),
     *          @OA\Property(property="data", type="object", description="data" ),
     *          @OA\Property(property="message", type="string", description="message" ),
     *          ),
     *       ),
     *
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
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *
     *     )
     */


    public function destroy(UserRequest $request)
    {
        try{
            $user = $this->userRepository->getUserByEmail($request->email);
            $user_id = $user->value('id');
            $user = User::find($user_id);
            $user->delete();
            
            return $this->successResponse($user->first_name." deleted!");
        }
        catch(Exception $e){
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

   
}