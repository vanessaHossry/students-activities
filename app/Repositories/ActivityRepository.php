<?php

namespace App\Repositories;
use App\Models\WeekDay;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\ActivityInterface;
use App\Http\Resources\ActivityWeekdayResource;

class ActivityRepository implements ActivityInterface
{
    public function getActivityBySlug($request){
        return Activity::where('slug',$request->activity_slug)->active()->first(); 
    }

    public function getDeletedActivityBySlug($request){
        return Activity::withTrashed()->where('slug',$request->activity_slug)->first(); 
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
        return Activity::active()->paginate(15);
    }

    public function getDeletedActivities(){
       // return Activity::onlyTrashed()->get('slug'); can not use in array on this
       return Activity::onlyTrashed()->pluck('slug')->toArray();
    }

    public function isDeletedActivity($request)
    {
        $trashedSlugs = $this->getDeletedActivities();
        $isDeleted = in_array($request->activity_slug, $trashedSlugs);
        return $isDeleted;
    }

    public function showActivityWeek($request)
    {
        $activities = Activity::query()->active();

        if (isset($request->weekday)) {
            $activities = $activities->whereHas('weekdays', function ($query) use ($request) {
            $query->where('slug', $request->weekday);
            });
        }
        if (isset($request->min_price)) {
            $activities = $activities->where('price', '>=', $request->min_price);
        }
        if (isset($request->max_price)) {
            $activities = $activities->where('price', '<=', $request->max_price);
        }

        return $activities->paginate($request->per_page);
    }
}
