<?php

namespace App\Traits;

trait CacheableTrait
{
    
    public function disableCache($isSkip = true)
    {
        $this->model->disableCache();
        return $this;
    }

}
