<?php

namespace App\Http\Controllers\Admin\V1;

use Exception;
use App\Models\WeekDay;
use App\Models\Activity;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Interfaces\ActivityInterface;
use App\Http\Controllers\Admin\V1\Docs;
use App\Http\Requests\admin\V1\ActivityWeekdayRequest;


class ActivitiesWeekdaysController extends Controller
{
    use ApiResponse, Docs;
    private $activityRepository;
    public function __construct(ActivityInterface $activityRepository)
    {
        $this->activityRepository = $activityRepository;
        
        $this->middleware('auth.apikey');
        $this->middleware('auth:api');
        $this->middleware('permission:sync-activity-week');
    }

    // --- store in activity - weekday
    /**
     
     * @OA\Post(
     * path="/admin/v1/store-activity-week",
     * tags={"Activity-Week"},
     * summary="user signup",
     * security={{ "APIKey": {} }},
     *     @OA\RequestBody(
     *           required=true,
     *           description="Body request needed to add user object",
     *            @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *               @OA\Property(property="activity_slug", description="activity"),
     *               @OA\Property(property="weekday",description="week day"),
     *               @OA\Property(property="start_time", description="start time"),
     *               @OA\Property(property="end_time",description="end time"),
     *              
     *            ),
     *        ),
     *    ),
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
     *
      */
    public function store(ActivityWeekdayRequest $request){
        try{
            $activity = $this->activityRepository->getActivityBySlug($request);
            $weekday_id = $this->activityRepository->getWeekDayBySlug($request);
            $res = DB::table('activities_week')->where('activity_id',$activity->id)->where('week_day_id',$weekday_id)->first();

            if(!empty($res))   
                return $this->errorResponse(__("messages.query_denied"),Response::HTTP_BAD_REQUEST);
            
            $res= $activity->weekdays()->attach($weekday_id,["start_time"=>$request->start_time, "end_time"=>$request->end_time]); 
            return $this->successResponse(__("messages.records_attached"));
            
            
        }
        catch(Exception $e){
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /*
    this update method takes activity slug and weekday as required parameters
    if the start time and end time are not specified, it detaches this weekday from the activity
    else it syncs the weekday to the activity
    */

    // --- update activity - weekday

    /**
     
     * @OA\Put(
     *     path="/admin/v1/update-activity-week/{activity_slug}",
     *     tags={"Activity-Week"},
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
     *    @OA\RequestBody(
     *           required=true,
     *           description="Body request needed to update activity weekdays",
     *            @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *               type="object",
     *                  @OA\Property(property="activity_hours",type="array", @OA\Items(
     *                  @OA\Property(property="weekday",type="string"),
     *                  @OA\Property(property="start_time",type="string"),
     *                  @OA\Property(property="end_time",type="string"),
     *                          ),
     *                     ),
     *                 ),
     *            ),
     *      ),
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

    public function update(ActivityWeekdayRequest $request)
    {
        try {
            $activity = $this->activityRepository->getActivityBySlug($request);
            $data = json_decode($request->getContent(), true);
            $syncData = array();
            foreach ($data['activity_hours'] as $item) {
                $weekday = $item['weekday'];
                $start_time = $item['start_time'];
                $end_time = $item['end_time'];
                $weekday_id = WeekDay::where('slug', $weekday)->value('id');
              
                $syncData[$weekday_id] = [

                    'start_time' => $start_time,
                    'end_time' => $end_time
                ];
          }
      
        $activity->weekdays()->sync($syncData);

        return $this->successResponse(__("messages.records_attached"));
            
            // --- hayde bt nazil the same start and end time for every combination activity-weekday provided
            // $activity = $this->activityRepository->getActivityBySlug($request);
            // $a = array(); 
            // foreach ($request->weekday as $value) {
            //     $weekday_id = WeekDay::where('slug', $value)->value('id'); 
            //     // $res = $this->activityRepository->getActivityWeek($request);
            //     // if (!empty($res))
            //     //     return $this->errorResponse(__("messages.query_denied"), Response::HTTP_BAD_REQUEST);

            //     array_push($a, $weekday_id);
            // }
            // $activity->weekdays()->syncWithPivotValues($a, ["start_time" => $request->start_time, "end_time" => $request->end_time]);
            // return $this->successResponse(__("messages.records_attached"));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

     /**
     
     * @OA\Put(
     *     path="/admin/v1/delete-activity-week/{activity_slug}",
     *     tags={"Activity-Week"},
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
     *    @OA\RequestBody(
     *           required=true,
     *           description="Body request needed to delete activity-weekday",
     *            @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *               type="object",
     *                 @OA\Property(property="weekday",type="string"),
     *                 ),
     *             ),
     *      ),
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

    public function destroy(ActivityWeekdayRequest $request)
    {
        try {
            $activity = $this->activityRepository->getActivityBySlug($request);
            //DB::enableQueryLog();
            $weekday = $request->weekday;
            $weekday_id = WeekDay::where('slug', $weekday)->value('id');
            $bool = $activity->weekdays()->detach($weekday_id);
            //$queryLog = DB::getQueryLog(); Log::info(json_encode($queryLog));
            if ($bool)
                return $this->successResponse(__("messages.record_detached"));
            else
                return $this->successResponse("something went wrong");

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
