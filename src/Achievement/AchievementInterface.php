<?php

namespace App\Achievement;

use App\Entity\Achievement;
use App\Entity\User;

interface AchievementInterface
{
    public function check(User $user): ?Achievement;
}
