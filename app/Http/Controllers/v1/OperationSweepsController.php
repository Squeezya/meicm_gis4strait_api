<?php

namespace App\Http\Controllers\v1;

use App\Models\v1\Operation;
use App\Models\v1\Sweep;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Validator;
use Webpatser\Uuid\Uuid;

/**
 * @Controller(prefix="v1")
 * @Resource("v1/operations.sweeps", only={"index","store"})
 * @Middleware("cors")
 * @Middleware("jwt.auth")
 */
class OperationSweepsController extends Controller
{
    function __construct(Sweep $sweep)
    {
        $this->sweep = $sweep;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $operationId)
    {
        $operation = Operation::findOrFail($operationId);
        $sweeps = $operation->sweeps()->get();
        return Response::json($sweeps);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $operationId)
    {
        $v = Validator::make($request->all(), Sweep::$rules);

        if ($v->fails())
        {
            return Response::json($v->errors(), 400);
        }

        $sweep = new Sweep();
        $sweep->fill($request->all());

        $operation = Operation::findOrFail($operationId);
        $sweep->operation_id = $operation->id;

        $sweep_id = Uuid::generate();
        $sweep->id = $sweep_id;

        $sweep->save();

        //needed as $utilizador do not have an ID yet
        $find_sweep = Sweep::find($sweep_id);
        return Response::json($find_sweep);
    }
}
