<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;
use Auth;
use Redirect;
use Session;
use Validator;
use Illuminate\Support\Facades\Input;

class MainController extends Controller {
public function login(Request $request) {
		$rules = array (
				
				'username' => 'required',
				'password' => 'required' 
		);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			return Redirect::back ()->withErrors ( $validator, 'login' )->withInput ();
		} else {
			if (Auth::attempt ( array (
					
					'name' => $request->get ( 'username' ),
					'password' => $request->get ( 'password' ) 
			) )) {
				session ( [ 
						
						'name' => $request->get ( 'username' ) 
				] );
				return Redirect::back ();
			} else {
				Session::flash ( 'message', "Invalid Credentials , Please try again." );
				return Redirect::back ();
			}
		}
	}
	public function register(Request $request) {
		$rules = array (
				'email' => 'required|unique:users|email',
				'name' => 'required|unique:users|alpha_num|min:4',
				'phone' => 'required|unique:users|alpha_num|max:15',
				'nationality' => 'required|min:4',
				'birthdate' => 'required|min:4',
				'password' => 'required|min:6|confirmed' 
		);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			return Redirect::back ()->withErrors ( $validator, 'register' )->withInput ();
		} else {
			$user = new User ();
			$user->name = $request->get ( 'name' );
			$user->email = $request->get ( 'email' );
			$user->phone = $request->get ( 'phone' );
			$user->nationality = $request->get ( 'nationality' );
			$user->birthdate = $request->get ( 'birthdate' );


			$user->password = Hash::make ( $request->get ( 'password' ) );
			$user->remember_token = $request->get ( '_token' );
			
			$user->save ();
			return Redirect::back ();
		}
	}
	public function logout() {
		Session::flush ();
		Auth::logout ();
		return Redirect::back ();
	}
}
