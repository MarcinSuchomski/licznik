<?php

namespace App\Repositories\Time;

use App\Models\Time;
use Illuminate\Database\Eloquent\Model;
use \App\Repositories\BaseRepository;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TimeRepository extends BaseRepository
{

    /**
     * @inheritDoc
     */
    public function getModel()
    {
        return new Time();
    }

    public function getCollectionByDates(array $request)
    {
        $startTime = " 00:00:01";
        $endTime = " 23:59:59";

        return  $this->getModel()::whereBetween('created_at', array($request['from'].$startTime, $request['to'].$endTime))->where('users_id', Auth::user()->id)->get();
    }

}
