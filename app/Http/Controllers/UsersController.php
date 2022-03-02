<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Validator;

class UsersController extends Controller
{

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'name' => 'required',
           'lastName' => 'required',
           'phone' => 'required'
        ]);

        if($validator->fails()){
            $messages = $validator->errors();
            return response()->json([
                'status' => 0,
                'message' => $messages
            ]);
        }

        $name = $request->get('name');
        $lastName = $request->get('lastName');
        $phone = $request->get('phone');
        if(ContactsController::checkPhone($phone)){
            if(!User::where('phone', $phone)->exists()) {
                User::create([
                    'name' => $name,
                    'lastName' => $lastName,
                    'phone' => $phone
                ]);
                return response()->json([
                    'status' => 1,
                    'message' => 'Se ha creado satisfactoriamente'
                ]);
            }else{
                return response()->json([
                    'status' => 0,
                    'message' => 'El teléfono ya existe'
                ]);
            }
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'El teléfono no es correcto'
            ]);
        }
    }

}
