<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\User;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // Add validation
        $this->validate($request,
            // Validation rules
            [
                'userName'      => 'required',
                'emailAddress'  => 'required|email|unique:users',
                'password'      => 'required'
            ],
            // Validation custom messages.
            [
                'required'  => 'Please fill attribute :attribute',
                'unique'    => 'This value already in use :attribute'
            ]
        );

        try {
            $hash = app()->make('hash');
            $username = $request->input('userName');
            $email = $request->input('emailAddress');
            $password = $hash->make($request->input('password'));

            $user = User::create([
                'userName'      => $username,
                'emailAddress'  => $email,
                'password'      => $password,
            ]);

            $res['status']  = true;
            $res['message'] = 'Registration success!';

            return response($res, 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            $res['status']  = false;
            $res['message'] = $ex->getMessage();

            return response($res, 500);
        }
    }

    public function get_user()
    {
        $user = User::all();
        if ($user) {
            $res['status']  = true;
            $res['message'] = $user;

            return response($res, 200);
        }else{
            $res['status']  = false;
            $res['message'] = 'No record found.';

            return response($res, 200);
        }
    }

    public function updatePassword($id, Request $request)
    {
        // Add validation
        $this->validate($request,
            // Validation rules
            [
                'password'  => 'required|confirmed',
                'password_confirmation'  => 'required|same:password'
            ],
            // Validation custom messages.
            [
                'required'  => 'Please fill attribute :attribute',
                'same'      => 'The :attribute and :other must match.'
            ]
        );

        try {
            $hash = app()->make('hash');
            $user = User::find($id);

            $user->password = $hash->make($request->input('password')); ;
            $user->save();

            $res['status']  = true;
            $res['message'] = 'Password changed!';

            return response($res, 200);

        } catch (\Illuminate\Database\QueryException $ex) {
            $res['status']  = false;
            $res['message'] = $ex->getMessage();

            return response($res, 500);
        }
    }

    public function search(Request $request)
    {
        // Add validation
        $this->validate($request,
            // Validation rules
            [
                'emailAddress'  => 'required'
            ],
            // Validation custom messages.
            [
                'required'  => 'Please fill attribute :attribute'
            ]
        );

        $email = $request->input('emailAddress');

        $user = User::where('emailAddress', 'like', '%'.$email.'%') -> get();
        if ($user->count() > 0) {

            $res['status']  = true;
            $res['message'] = $user;

            return response($res, 200);
        }else{
            $res['status']  = false;
            $res['message'] = 'No record found.';

            return response($res, 200);
        }
    }

    public function destroy($email)
    {
        try {

            $user = User::where('emailAddress', $email) -> first();
            if ($user) {

                $user->delete();

                $res['status']  = true;
                $res['message'] = 'User Deleted';

                return response($res, 200);
            }else{
                $res['status']  = false;
                $res['message'] = 'No record found.';

                return response($res);
            }

        } catch (\Illuminate\Database\QueryException $ex) {
            $res['status']  = false;
            $res['message'] = $ex->getMessage();

            return response($res, 500);
        }
    }
}