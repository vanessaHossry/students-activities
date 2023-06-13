<?php

namespace App\Interfaces;

interface UserInterface
{
    public function getSelf();
    public function store($request);
}
