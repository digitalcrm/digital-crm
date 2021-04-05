<?php

namespace App\Traits;

trait DefaultProfile
{
    protected function defaultProfilePhotoUrl($name = null)
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=7F9CF5&background=EBF4AA';
    }
}
