<?php

namespace App\Http\Controllers\client\v1;

use App\Http\Resources\ActivityWeekdayResource;
use App\Models\Activity;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Interfaces\ActivityInterface;

class ActivitiesWeekdaysController extends Controller
{
    use ApiResponse, Docs;
    private $activityRepository;
    public function __construct(ActivityInterface $activityRepository)
    {
        $this->activityRepository = $activityRepository;
        $this->middleware('auth.apikey');
        $this->middleware('auth:api');
    }

      /**
     * @OA\Get(
     *      path="/client/v1/get-activities-weekdays",
     *      operationId="getAllActivitiesWeekdays",
     *      tags={"Activity-Week"},
     *      summary="Rertrieve all activities with week day",
     *       security={{ "APIKey": {} }},
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
    public function index()
    {
        //DB::enableQueryLog();
        $a = Activity::withCount('weekdays')->with('weekdays')->get();
       // $ql = DB::getQueryLog();
       // Log::info(json_encode($ql));
        return $this->successResponse(ActivityWeekdayResource::collection($a));

        // to only retrieve particular values (ma ela 3aze using resource is easier)

        // $a = Activity::withCount('weekdays')->with('weekdays')->get()->toArray();
        // $b = array();
        // foreach ($a as $value) {
        //     Log::info($value);

        //     if (!empty($value['weekdays'])) {
        //         foreach ($value['weekdays'] as $w) {
        //             //  Log::info($w);

        //             array_push($b, [$value['slug'], $value['price'], $value['weekdays_count'], $w['slug']]);


        //         }
        //     } else
        //         array_push($b, [$value['slug'], $value['price'], $value['weekdays_count']]);
        // }
        // return $this->successResponse($b);



        // does not do anything

        //Log::info(json_encode($b));
        // return $userRole['roles']->value('name');
        //  return $this->successResponse(Activity::withCount('weekdays')->with(['weekdays' => function ($query) {
        //         $query->select('slug', 'name');
        //     }])->get());

        //return $this->successResponse(Activity::withCount('weekdays')->with('weekdays')->get('weekdays["slug"]'));
    }
}
