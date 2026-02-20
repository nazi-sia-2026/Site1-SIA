<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

Class UserController extends Controller {
    use ApiResponser;
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function getUsers(){
        $users = User::all();
        return response()->json($users,200);
    }
    public function index(){
        $users = User::all();
        return response()->json($users,200);
    }
    public function add(Request $request){
        $rules = [
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6|max:20',
        ];

        $this->validate($request,$rules);
        $user = User::create($request->all());
        return response()->json($user,201);
    }
    public function show($id)
    {   $user = User::findOrFail($id);
        return $this->successResponse($user);
    }
         // OLD CODE
         /*
        $user = User::where('id',$id)->first();
        if(!$user){
            return response()->json(['message' => 'User not Found.'],404);
        }
        return response()->json($user,200);
    }*/
    public function delete($id){
        $user = User::findOrFail($id);
        $user->delete();
        return $this->errorResponse('User ID does not exists.', Response::HTTP_NOT_FOUND);

        //old code
        /*
        $user = User::where('id',$id)->first();
        if($user) { 
            $user->delete();
            return response()->json(['message' => 'User deleted successfully.'],200);
        }
        return response()->json(['message' => 'User not Found.'],404);*/
    }
    public function update($id, Request $request){
        $rules = [
            'username' => 'string|unique:users,username,'.$id.'|max:20',
            'password' => 'string|min:6|max:20',];

            $this->validate($request, $rules);
            $user = User::findOrFail($id);

            $user->fill($request->all());

            //if no changes happened.
            if ($user->isClean()) {
                return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $user->save();
            return $this->successResponse($user);
        //old code
        /*
        $user = User::where('id', $id)->first();
        if(!$user){ 
            return response()->json(['message' => 'User not Found.'],404);
        }

        $rules = [
            'username' => 'string|unique:users,username,'.$id.'|max:20',
            'password' => 'string|min:6|max:20',
        ];
        $this->validate($request, $rules);
        $user->update($request->all());
        return response()->json($user,200);*/
    }
}
