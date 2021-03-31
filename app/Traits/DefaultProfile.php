<?php

namespace App\Traits;

trait DefaultProfile
{
    public function profileUrl()
    {
        return $this->picture
            ? url($this->picture)
            : $this->defaultProfilePhotoUrl();
    }

    protected function defaultProfilePhotoUrl()
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4AA';
    }
}
