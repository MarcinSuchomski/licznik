<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Time;
use App\Services\Time\TimeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TimeController extends Controller
{

    /**
     * @var TimeService
     */
    public $service;

    /**
     * ActivationController constructor.
     * @param TimeService $service
     */
    public function __construct(TimeService $service)
    {
        $this->service = $service;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return $this->service->create($request);
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Time $time
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request)
    {
        $header = $request->header('Authorization');
        Log::info($header);
        $request = $request->input();

        if (isset($request['from'], $request['to'])) {

            return $this->service->getCollectionByDates($request);

        }

        $request['where'] = array(
            [
                "column" => "users_id",
                "value" => Auth::user()->id
            ],
        );


        return $this->service->getCollection($request);

    }

}
