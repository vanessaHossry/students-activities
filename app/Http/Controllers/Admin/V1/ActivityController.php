<?php

namespace App\Http\Controllers\admin\v1;

use Exception;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Interfaces\ActivityInterface;
use App\Http\Requests\admin\v1\ActivityRequest;

class ActivityController extends Controller
{   
    use ApiResponse, Docs;
    private $activityRepository;
    public function __construct(ActivityInterface $activityRepository)
    {
        $this->middleware('auth.apikey');
        $this->middleware('auth:api',["except"]);
        $this->activityRepository = $activityRepository;
        
        $this->middleware('permission:create-activity', ['only' => ['store']]);
        $this->middleware('permission:destroy-activity', ['only' => ['destroy','restore']]);
        $this->middleware('permission:update-activity-price', ['only' => ['update']]);
       
    }
    // --- store
    	 /**
        
        * @OA\Post(
        * path="/admin/v1/store-activity",
        * operationId="createActivity",
        * tags={"Activity"},
        * security={{ "APIKey": {} }},
        *     @OA\RequestBody(
        *           required=true,
        *           description="Body request needed to add activity object",
        *            @OA\MediaType(
        *            mediaType="application/json",
        *            @OA\Schema(
        *               required={"name", "price"},
        *               @OA\Property(property="name",description="name"),
        *               @OA\Property(property="price", description="price", type="double"),
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
            public function store(ActivityRequest $request){
                try{
                    $activity = $this->activityRepository->storeActivity($request);
                    return $this->successResponse($activity);
                }
                catch(Exception $e){
                    return $this->errorResponse($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
    // --- index
    /**
     
     * @OA\Get(
     *      path="/admin/v1/get-activities",
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
     *      path="/admin/v1/get-activity-by-slug/{activity_slug}",
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
    // --- update
    /**
     
     * @OA\Put(
     *     path="/admin/v1/update-activity-price/{activity_slug}",
     *     tags={"Activity"},
     *     security={{ "APIKey": {} }},
     *      @OA\Parameter(
     *         name="activity_slug",
     *         in="path",
     *         description="the activity to update",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *       @OA\RequestBody(
     *           required=true,
     *           description="Body request needed to update activity price",
     *            @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *               type="object",
     *               @OA\Property(property="price",description="price",type="double"),
     *              ),
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
     *          @OA\Property(property="success", type="boolean",      description="status" ),
     *          @OA\Property(property="data",type="array",  @OA\Items( type="object"  ),description="data" ),
     *          @OA\Property(property="message", type="string", description="message" ),
     *          ),
     *       ),
     * )
     */
        public function update(ActivityRequest $request){
            try{
                //return $request->price; // was not working with multipart form data
               
                $activity = $this->activityRepository->getActivityBySlug($request);
                if(!empty($activity)){
                $activity->update(['price' => $request->price]);
                return $this->successResponse($activity);
                }
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

    // --- delete
    /**
     
     * @OA\Delete(
     *      path="/admin/v1/delete-activity/{activity_slug}",
     *      operationId="deleteActivityBySlug",
     *      tags={"Activity"},
     *      summary="Delete a specific activity",
     *      security={{ "APIKey": {} }},
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

     public function destroy(ActivityRequest $request){
        try{
            $activity = $this->activityRepository->getActivityBySlug($request);
            if(!empty($activity)){
            $activity->delete();
            return $this->successResponse($activity->name." successfully deleted");
            }
            $isDeleted = $this->activityRepository->isDeletedActivity($request);
                if($isDeleted){
                    return $this->successResponse("activity trashed already");
                }
            return $this->errorResponse(__("messages.query_denied"),Response::HTTP_NOT_FOUND);

        } catch(Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }


     }

     // --- restore deleted activity
         /**
     
     * @OA\Patch(
     *     path="/admin/v1/restore-activity/{activity_slug}",
     *     operationId="restoreActivity",
     *     summary="restore activity",
     *     tags={"Activity"},
     *     security={{ "APIKey": {} }},
     *     @OA\Parameter(
     *         name="activity_slug",
     *         in="path",
     *         description="activity slug",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *           @OA\Response(
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

 public function restore(ActivityRequest $request){
    try{
        
       
        $isDeleted = $this->activityRepository->isDeletedActivity($request);
        
        if($isDeleted) {
        $activity = $this->activityRepository->getDeletedActivityBySlug($request); 
        $activity->restore();
        return $this->successResponse($activity->name." successfully restored");
        }

        $activity = $this->activityRepository->getActivityBySlug($request);
        if(!empty($activity)){
            return $this->successResponse($activity->name." already active");
        }
        return $this->errorResponse(__("messages.query_denied"),Response::HTTP_NOT_FOUND);

    } catch(Exception $e){
        return $this->errorResponse($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
    }
 }

     // --- deactivate activity
          /**
     
     * @OA\Patch(
     *     path="/admin/v1/deactivate-activity/{activity_slug}",
     *     operationId="deactivateActivity",
     *     summary="deactivate activity",
     *     tags={"Activity"},
     *     security={{ "APIKey": {} }},
     *     @OA\Parameter(
     *         name="activity_slug",
     *         in="path",
     *         description="activity slug",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *           @OA\Response(
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

     public function deactivate(ActivityRequest $request){
        try{
            $activity = $this->activityRepository->getActivityBySlug($request);
            $activity->update(['is_active' => false]);
            return $this->successResponse("activity deactivated");
        }catch(Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
     }

       // --- activate activity
          /**
     
     * @OA\Patch(
     *     path="/admin/v1/activate-activity/{activity_slug}",
     *     operationId="activateActivity",
     *     summary="activate activity",
     *     tags={"Activity"},
     *     security={{ "APIKey": {} }},
     *     @OA\Parameter(
     *         name="activity_slug",
     *         in="path",
     *         description="activity slug",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *           @OA\Response(
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

     public function activate(ActivityRequest $request){
        try{
            $activity = $this->activityRepository->getActivityBySlug($request);
            $activity->update(['is_active' => true]);
            return $this->successResponse("activity activated");
        }catch(Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_INTERNAL_SERVER_ERROR);
        }
     }

}
