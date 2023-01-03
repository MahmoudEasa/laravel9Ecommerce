<?php

namespace App\Observers;

use App\Models\MainCategory;

class MainCategoryObserver
{
    /**
     * Handle the MainCategory "created" event.
     *
     * @param  \App\Models\MainCategory  $mainCategory
     * @return void
     */
    public function created(MainCategory $mainCategory)
    {
        //
    }

    /**
     * Handle the MainCategory "updated" event.
     *
     * @param  \App\Models\MainCategory  $mainCategory
     * @return void
     */
    public function updated(MainCategory $mainCategory)
    {
        if($mainCategory->active == 0) {
            $mainCategory->vendors()->update(['active' => $mainCategory->active]);
        }
        $mainCategory->otherLangs()->update(['active' => $mainCategory->active]);
    }

    /**
     * Handle the MainCategory "deleted" event.
     *
     * @param  \App\Models\MainCategory  $mainCategory
     * @return void
     */
    public function deleted(MainCategory $mainCategory)
    {
        $mainCategory->otherLangs()->delete();
    }

    /**
     * Handle the MainCategory "restored" event.
     *
     * @param  \App\Models\MainCategory  $mainCategory
     * @return void
     */
    public function restored(MainCategory $mainCategory)
    {
        //
    }

    /**
     * Handle the MainCategory "force deleted" event.
     *
     * @param  \App\Models\MainCategory  $mainCategory
     * @return void
     */
    public function forceDeleted(MainCategory $mainCategory)
    {
        //
    }
}