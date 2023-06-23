<?php

namespace App\Interfaces;

interface ActivityInterface
{
    public function getActivityBySlug($request);
    public function getWeekDayBySlug($request);
    public function getActivityWeek($request);
}
