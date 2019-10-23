<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Group;
use Illuminate\Database\QueryException;
use Sentinel;
use DB;

class GroupsComposer
{
    protected $groups;

    function __construct()
    {
            $this->groups = Group::pluck('group','id')->toArray();
    }

    public function compose(View $view)
    {
        $view->with('groups',$this->groups);
    }
}