<?php


namespace App\Observers;

use App\Models\Link;
use Cache;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class LinkObserver
{
    public function saved(Link $link){
        Cache::forget($link->cache_key);
    }
}
