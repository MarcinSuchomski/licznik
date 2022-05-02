<?php

namespace App\Services\Time;

use App\Models\Time;
use App\Repositories\Time\TimeRepository;
use App\Services\BaseService;
use App\Validators\Time\TimeValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\DataParsers\DataTablesParser;

class TimeService extends BaseService
{

    /**
     * UserService constructor.
     * @param TimeRepository $repo
     * @param TimeValidator $validator
     */
    public function __construct(TimeRepository $repo, TimeValidator $validator)
    {
        $this->repo = $repo;
        $this->validator = $validator;
        $this->grid_parser = new DataTablesParser();
    }

    public function create(Request $request)
    {
        $requestData = $request->all();

        $validateObject = $this->validator->validate($requestData);
        if (!$validateObject->valid) {
            return response()->json([
                'errors' => $validateObject->errors
            ], 422);
        }

        $requestData['users_id'] = Auth::user()->id;

        Time::create($requestData);

        return response([ 'status' => 'success', 'message' => 'successfully added task.' ], 200);
    }

    public function getCollectionByDates(array $request)
    {
        return  $this->grid_parser->parseOutputFromRepositoryFormat($this->repo->getCollectionByDates($request)->toArray(),array(),$this->repo->getCollectionByDates($request)->count());
    }


}
