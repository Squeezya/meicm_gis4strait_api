<?php

namespace App\Http\Controllers\v1;

use App\Models\v1\Operation;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Validator;
use Webpatser\Uuid\Uuid;

/**
 * @Controller(prefix="v1")
 * @Resource("v1/operations", only={"index", "show", "store", "destroy", "update"})
 * @Middleware("cors")
 * @Middleware("jwt.auth")
 */
class OperationController extends Controller
{
    function __construct(Operation $operation)
    {
        $this->operation = $operation;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->get('perPage');
        $perPage = $perPage ?: 10;
        $operations = Operation::with('sweeps')->orderBy('created_at', 'desc')->paginate(intval($perPage));
        return response()->json($operations);
    }

    /**
     * Display a single element of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $operationId)
    {
        $operation = Operation::findOrFail($operationId);
        return Response::json($operation);
    }

    /**
     * Delete a single element of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $operationId)
    {
        $operation = Operation::findOrFail($operationId);
        $operation->delete();
        return response('Deleted successfully', 204)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * Update a single element of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $operationId)
    {
        $operation = Operation::findOrFail($operationId);
        $operation->fill($request->all());
        $operation->save();
        return Response::json($operation);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), Operation::$rules);

        if ($v->fails()) {
            return Response::json($v->errors(), 400);
        }

        $operation = new Operation();
        $operation->fill($request->all());

        $operation_id = Uuid::generate();
        $operation->id = $operation_id;

        $operation->save();

        //needed as $utilizador do not have an ID yet
        $find_operation = Operation::find($operation_id);
        return Response::json($find_operation);
    }
}
