<?php

namespace App\Http\Controllers\admin\v1;

use App\Interfaces\RoleInterface;
use App\Interfaces\UserInterface;
use Exception;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\admin\v1\RoleRequest;

class RoleController extends Controller
{
    
    use ApiResponse, Docs;
    private $roleRepository;
    private $userRepository;
    public function __construct(RoleInterface $roleRepository,UserInterface $userRepository)
    {
        $this->middleware('auth.apikey');
        $this->middleware('auth:api');
        $this->roleRepository=$roleRepository;
        $this->userRepository=$userRepository;
    }
     // --- index
    /**
     
     * @OA\Get(
     *      path="/admin/v1/get-roles",
     *      operationId="getAllRoles",
     *      tags={"Role"},
     *      summary="Retrieve all roles",
     *      security={{ "APIKey": {} }},
     *
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 description="Status indicating the success of the operation"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="Updated user data"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 description="Status indicating the failure of the operation"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Error message"
     *             )
     *         )
     *     )
     * )
     */
    public function index(){
        try{
            $roles = $this->roleRepository->getRoles();
            return $this->successResponse($roles);
        }
        catch(Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    // give permission to user 
    /**
     
 * @OA\Patch(
 *     path="/admin/v1/user/{user_email}/permission/{permission}",
 *     operationId="givePermission",
 *     summary="Give permission to a user",
 *     tags={"Permissions"},
 *     security={{ "APIKey": {} }},
 *     @OA\Parameter(
 *         name="user_email",
 *         in="path",
 *         description="User email",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="permission",
 *         in="path",
 *         description="Permission ",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *                 @OA\Response(
 *          response="200",
 *          description="Successful Operation",
 *          @OA\JsonContent(
 *          type="object",
 *          @OA\Property(property="success", type="boolean", description="status" ),
 *          @OA\Property(property="data", type="object", description="data" ),
 *          @OA\Property(property="message", type="string", description="message" ),
 *          ),
 *        ),
 *
 *
 *
 *
 *       @OA\Response(
 *          response="422",
 *          description="Unprocessable Entity",
 *          @OA\JsonContent(
 *          type="object",
 *          @OA\Property(property="success", type="boolean", description="status" ),
 *          @OA\Property(property="data",type="array",  @OA\Items( type="object"  ),description="data" ),
 *          @OA\Property(property="message", type="string", description="message" ),
 *          ),
 *
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
 *
 * )
 */
    public function givePermissionToUser(RoleRequest $request){
       // return $request->user_email. " ".$request->permission;
      try{
        $user = $this->userRepository->getUserByEmail($request->user_email);
        if(!empty($user)){
        $user->givePermissionTo($request->permission);
        return $this->successResponse($user->first_name." granted permission successfully");
        }
        return "cc";
    }catch(Exception $e){
        return $this->errorResponse($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
    }
        

    }
}
