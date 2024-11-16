<?php

namespace App\CustomJobs;

class DummyJob
{
    public function sayHello($name)
    {
        return "Hello, $name!";
    }
}
