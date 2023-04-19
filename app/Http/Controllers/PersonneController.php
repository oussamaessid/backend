<?php

namespace App\Http\Controllers;

use App\Mail\TestEmail;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Personne;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PersonneController extends Controller
{
    /*  public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['loginUser', 'createUser']]);
    } */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|unique:personne',
            'password' => 'required|string|min:6'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $personne = User::create(array_merge($validator->validated(), ['password' => bcrypt($request->password)]));
        return response()->json(['message' => 'user successfuly registered'], 201);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if (!$token = Auth::guard('api')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }
    public function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token-type' => 'bearer',
            //'expires_in' => Auth::guard('web')->factory()->getTTL() * 60,
            'email' => Auth::guard('api')->user()->email,
            'password' => Auth::guard('api')->user()->password,
            'status' => 200

        ]);
    }


    public function profile()
    {
        return response()->json(Auth::guard('api')->user());
    }
    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'user logged out successfully']);
    }
    //////////////////////////
    ////////////////////////////
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make(
                $request->all(),
                [
                    'nom' => 'required',
                    'prénom' => 'required',
                    'email' => 'required|string|email|unique:personne',
                    'password' => 'required|string|min:6',
                    'role' => 'required',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = Personne::create([
                'nom' => $request->nom,
                'prénom' => $request->prénom,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'statut' => $statut = 'non validé',


            ]);
            $userv = ['email' => $request->email, 'nom' => $request->nom,  'prénom' => $request->prénom, 'role' => $request->role];
            Mail::to('aya@gmail.com')->send(new TestEmail($userv));

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            //var_dump($request->only(['email', 'password']));
            //$request->password =  decrypt($request->password);
            // $password = $request->input('password');

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = Personne::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'user' => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function index()
    {
        $users = User::all();
        return response()->json([
            'status' => 200,
            'message' => $users,
        ]);
    }
    public function store(Request $request)
    {
        $users = new Personne();
        $users->id = $request->input('id');
        $users->nom = $request->input('nom');
        $users->prénom = $request->input('prénom');
        $users->email = $request->input('email');

        $users->password = bcrypt($request->password);
        $users->role = $request->input('role');
        $users->statut = 'non validé';
        $users->save();
        $userv = ['email' => $request->email, 'nom' => $request->nom, 'prénom' => $request->prénom, 'role' => $request->role];
        Mail::to('test@mail.test')->send(new TestEmail($userv));

        return response()->json([
            'status' => 200,
            'message' => 'users added succesuffly',
        ]);
    }
    public function edit($id)
    {
        $users = Personne::find($id);
        return response()->json([
            'status' => 200,
            'user' => $users,
        ]);
    }
    public function update(Request $request, $id)
    {

        $users =  Personne::find($id);

        $users->id = $request->input('id');
        $users->nom = $request->input('nom');
        $users->prénom = $request->input('prénom');
        $users->email = $request->input('email');

        $users->password = bcrypt($request->password);
        $users->role = $request->input('role');
        $users->statut = $request->input('statut');
        $users->update();
        return response()->json([
            'status' => 200,
            'message' => 'user updated succesuffly',
        ]);
    }
    public function delete($id)
    {
        $users = Personne::find($id);
        $users->delete();
        return response()->json([
            'status' => 200,
            'message' => 'user deleted succesuffly',
        ]);
    }
    public function valider(Request $request, $id)
    {

        $users =  Personne::find($id);

        $users->statut = 'validé';
        $users->update();
        return response()->json([
            'status' => 200,
            'message' => 'user validé succesuffly',
        ]);
    }
    public function désactiver(Request $request, $id)
    {

        $users =  Personne::find($id);

        $users->statut = 'désactivé';
        $users->update();
        return response()->json([
            'status' => 200,
            'message' => 'user désactivé succesuffly',
        ]);
    }
}
