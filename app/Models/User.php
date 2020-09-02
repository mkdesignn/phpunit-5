<?php

namespace App\Models;

class User
{

    public $verified;

    public function getCurrentUser()
    {
        return $this;
    }

    public function update($inputs = [])
    {
        $this->verified = isset($inputs['verified']) ? $inputs['verified'] : $this->verified;
    }

}