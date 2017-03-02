<?php

namespace App\Http\Controllers\v1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Webpatser\Uuid\Uuid;

/**
 * @Controller(prefix="v1")
 * @Resource("v1/users", only={"index","store"})
 */
class UsersController extends Controller
{
    function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
	\Log::info("users request. UsersController > index");
        $users = User::all();
        return Response::json($users);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), User::$rules);

        if ($v->fails())
        {
            return Response::json($v->errors(), 400);
        }

        $user = new User();
        $user->fill($request->all());

        $user->password = Hash::make($user->password);
        $user_id = Uuid::generate();
        $user->id = $user_id;

        $user->save();

        //needed as $utilizador do not have an ID yet
        $find_user = User::find($user_id);
        return Response::json($find_user);
    }

    /**
     * Show the Index Page
     * @Post("/login")
     */
    public function login(Request $request)
    {
        $credentials = Input::only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return Response::json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $user = Auth::user();

        // all good so return the token
        return response()->json(compact(['token', 'user']));
    }

    /**
     * Show the Index Page
     * @Post("/restore-session")
     * @Middleware("jwt.auth")
     */
    public function userFromToken(Request $request)
    {
        $user = Auth::user();

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }
}
