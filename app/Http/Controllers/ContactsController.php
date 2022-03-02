<?php

namespace App\Http\Controllers;

use App\Contacts;
use App\ContactUser;
use Illuminate\Http\Request;
use Validator;

class ContactsController extends Controller
{

    public function show(Request $request)
    {
        $user_id = $request->get('user_id');
        $contacts = ContactUser::with('contact:contactName,phone')->where('user_id', $user_id)->get();
        return response()->json($contacts->pluck('contact'));
    }

    public function showBetween(Request $request)
    {
        $user_id_1 = $request->get('user_id_1');
        $user_id_2 = $request->get('user_id_2');
        $contacts = ContactUser::with('contact:contactName,phone')
        ->whereRaw('user_id = '.$user_id_1.' AND contact_id IN (SELECT contact_id FROM contact_users WHERE user_id = '.$user_id_2.')
                        OR user_id = '.$user_id_2.' AND contact_id IN (SELECT contact_id FROM contact_users WHERE user_id = '.$user_id_1.')')
        ->get()->unique('contact_id');
        return response()->json($contacts->pluck('contact'));
    }

    public function updateCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contacts' => 'required',
            'user_id' => 'required',
        ]);

        if($validator->fails()){
            $messages = $validator->errors();
            return response()->json([
                'status' => 0,
                'message' => $messages
            ]);
        }

        $contacts = $request->get('contacts');
        $user_id = $request->get('user_id');
        $error = [];
        foreach($contacts as $contact){
            $contactName = $contact['contactName'];
            $phone = $contact['phone'];
            if($this::checkPhone($phone)){
                $updateCreateContact = Contacts::where('phone', $phone)->first();
                if(!$updateCreateContact){
                    $newContact = Contacts::create([
                        'contactName' => $contactName,
                        'phone' => $phone
                    ]);
                    ContactUser::create([
                        'user_id' => $user_id,
                        'contact_id' => $newContact->id
                    ]);
                }else{
                    $updateCreateContact->update([
                        'contactName' => $contactName,
                        'phone' => $phone
                    ]);
                }
            }else{
                $error[] = [
                    'contactName' => $contactName,
                    'phone' => $phone,
                    'message' => 'El teléfono no es correcto'
                ];
            }
        }
        if(count($error) == 0){
            return response()->json([
               'status' => 1,
               'message' => 'Se ha creado satisfactoriamente'
            ]);
        }else{
            return response()->json([
                'status' => 0,
                'message' => 'No se han podido crear los siguientes contactos',
                'data' => $error
            ]);
        }
    }


    public static function checkPhone($phone)
    {
        $data = [
            'user-id' => 100,
            'api-key' => '5ycOia48iAco56wXyE7yhoVcCMuf2wM1D0oUko2YDJ8DVb5R',
            'number' => $phone
        ];
        $url = 'https://neutrinoapi.net/phone-validate';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($ch);
        curl_close($ch);
        if(isset(json_decode($content)->valid) && json_decode($content)->valid == true){
            return true;
        }else{
            return false;
        }
    }

}
