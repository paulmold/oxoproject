<?php

namespace controller;

class IndexController
{
    public function __invoke()
    {
        require_once("view/home.html");
    }
}