<?php

namespace App\Interfaces;

interface UserInterface
{
    public function getSelf();
    public function store($request);
    public function index();

    public function show($request);

    public function getDeleted();

    public function getUserByEmail($email);
    public function getRoleByEmail($email);
}
