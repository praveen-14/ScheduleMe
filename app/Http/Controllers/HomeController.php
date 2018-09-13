<?php

namespace App\Http\Controllers;

use App\Allocation;
use App\Developer;
use App\User;
use App\ProjectStaff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }
    public function showDeveloperHome()
    {
        return view('developerHome');
    }
    public function showProjectManagerHome()
    {
        return view('projectManagerHome');
    }
    public function doLogin(Request $request)
    {

        $rules = array(
            'email' => 'required|email', // make sure the email is an actual email
            'password' => 'required|alphaNum|min:3' // password can only be alphanumeric and has to be greater than 3 characters
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::to('login')
                ->withErrors($validator)// send back all errors to the login form
                ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        } else {

            // create our user data for the authentication
            $userdata = array(
                'email' => Input::get('email'),
                'password' => Input::get('password')
            );

            // attempt to do the login
            if (Auth::attempt($userdata)) {
                if(User::find(Auth::user()->id)->projectManager){
                    return Redirect::to('projectManagerHome');
                }else if(User::find(Auth::user()->id)->developer){
                    return Redirect::to('developerHome');
                }

            } else {

                // validation not successful, send back to form

                return Redirect::to('login');

            }


        }

    }
}
