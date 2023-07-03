<?php

namespace App\Http\Controllers\client\v1;

use Exception;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Interfaces\ActivityInterface;
use App\Http\Requests\client\v1\ActivityRequest;

class ActivityController extends Controller
{
    use ApiResponse, Docs;
    private $activityRepository;
    public function __construct(ActivityInterface $activityRepository)
    {
        $this->middleware('auth.apikey');
        $this->middleware('auth:api',["except"]);
        $this->activityRepository = $activityRepository;
       //$this->middleware('permission:user.read', ['only' => ['getSelf']]);
       
    }

      // --- index
    /**
     
     * @OA\Get(
     *      path="/client/v1/get-activities",
     *      operationId="getAllActivities",
     *      tags={"Activity"},
     *      summary="Rertrieve all activities",
     *      security={{ "APIKey": {} }},
     *
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
     *
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *
     *     )
     */
    public function index(){
        try{
            $activities = $this->activityRepository->getActivities();
            return $this->successResponse($activities);
        }
        catch(Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


// --- show
  /**
   
 * @OA\Get(
 *      path="/client/v1/get-activity-by-slug/{activity_slug}",
 *      operationId="getActivityBySlug",
 *      tags={"Activity"},
 *      summary="Retrieve specific activity",
 *       security={{ "APIKey": {} }},
 *      @OA\Parameter(
 *        name="activity_slug", in="path",required=true, @OA\Schema(type="string")
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

public function show(ActivityRequest $request){
    try{
        $activity = $this->activityRepository->getActivityBySlug($request); 
        if(!empty($activity))return $this->successResponse($activity);

        $isDeleted = $this->activityRepository->isDeletedActivity($request);
        if($isDeleted){
            return $this->successResponse("activity trashed");
        }

        return $this->errorResponse(__("messages.query_denied"),Response::HTTP_NOT_FOUND);
    }
    catch(Exception $e){
        return $this->errorResponse($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
}
