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

    public function storeActivity($request){
        $activity = Activity::create([
            "name" => $request->name,
            "price" => $request->price,
        ]);
        return $activity;
    }
    public function getActivities(){
        return Activity::paginate(15);
    }

    public function getDeletedActivities(){
       // return Activity::onlyTrashed()->get('slug'); can not use in array on this
       return Activity::onlyTrashed()->pluck('slug')->toArray();
    }

    public function isDeletedActivity($request){
        $trashedSlugs= $this->getDeletedActivities();
       $isDeleted = in_array($request->activity_slug,$trashedSlugs);
        return $isDeleted;
    }
}
