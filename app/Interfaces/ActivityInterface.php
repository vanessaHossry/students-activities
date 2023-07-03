<?php

namespace App\Interfaces;

interface ActivityInterface
{
    public function getActivityBySlug($request);
    public function getWeekDayBySlug($request);
    public function getActivityWeek($request);
    public function storeActivity($request);

    public function getActivities();
    public function getDeletedActivities();
    public function isDeletedActivity($request);
    
}
