<?php

namespace App\Http\Controllers\Admin\V1;

use Exception;
use App\Models\WeekDay;
use App\Models\Activity;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\ActivityInterface;
use App\Http\Controllers\Admin\V1\Docs;
use App\Http\Requests\admin\V1\ActivityRequest;

class ActivitiesController extends Controller
{
    use ApiResponse, Docs;
    private $activityRepository;
    public function __construct(ActivityInterface $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    // --- store in activity - weekday
    /**
     
     * @OA\Post(
     * path="/admin/v1/storeActivityWeek",
     * tags={"Activity"},
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
    public function store(ActivityRequest $request){
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

    // --- update activity - weekday

    /**
     
     * @OA\Put(
     *     path="/admin/v1/update-activity-week/{activity_slug}",
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
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="weekday",
     *                     type = "array",
     *                     @OA\Items(
     *                         type="string",                       
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="start_time",
     *                     type="string",
     *                     example="09:00"
     *                 ),
     *                 @OA\Property(
     *                     property="end_time",
     *                     type="string",
     *                     example="09:00"
     *                     
     *                 )
     *             )
     *         )
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

    public function update(ActivityRequest $request)
    {
        try { 
            $activity = $this->activityRepository->getActivityBySlug($request);
            $a = array(); 
            foreach ($request->weekday as $value) {
                $weekday_id = WeekDay::where('slug', $value)->value('id'); 
                $res = $this->activityRepository->getActivityWeek($request);
                if (!empty($res))
                    return $this->errorResponse(__("messages.query_denied"), Response::HTTP_BAD_REQUEST);

                array_push($a, $weekday_id);
            }
            $activity->weekdays()->syncWithPivotValues($a, ["start_time" => $request->start_time, "end_time" => $request->end_time]);
            return $this->successResponse(__("messages.records_attached"));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
