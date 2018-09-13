<?php

namespace App\Http\Controllers\Auth;

use App\Developer;
use App\User;
use App\ProjectManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        if($data['role']=="projectManager"){

            $projectManager = new ProjectManager();
//            $tempUser = User::where('email',$data['email'])->first();
            $projectManager->id=$user['id'];
            $projectManager->analysisSkill=5;
            $projectManager->designSkill=5;
            $projectManager->implementingSkill=5;
            $projectManager->testingSkill=5;
            $projectManager->save();
        }elseif ($data['role']=='developer'){

            $developer = new Developer();
//            $tempUser = User::where('email',$data['email'])->first();
            $developer->id=$user['id'];
            $developer->analysisSkill=5;
            $developer->designSkill=5;
            $developer->implementingSkill=5;
            $developer->testingSkill=5;
            $developer->save();
        }

        return $user;

    }
}
