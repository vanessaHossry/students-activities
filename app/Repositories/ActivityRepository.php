<?php

namespace App\Repositories;
use App\Models\WeekDay;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ActivityInterface;

class ActivityRepository implements ActivityInterface
{
    public function getActivityBySlug($request){
        return Activity::where('slug',$request->activity_slug)->first(); 
    }
    public function getWeekDayBySlug($request){
        return WeekDay::where('slug',$request->weekday)->value('id');
    }
    public function getActivityWeek($request){
        $activity = $this->getActivityBySlug($request);
        $weekday_id=$this->getWeekDayBySlug($request);
        return  DB::table('activities_week')->where('activity_id',$activity->id)->where('week_day_id',$weekday_id)->first();
    }
}
