<?php

namespace Tests\Feature;

use App\Http\Controllers\ContactsController;
use Illuminate\Http\Request;
use Tests\TestCase;

class ContactTest extends TestCase
{
    public function testSaveContact()
    {
        try {
            $request = new Request();
            $request->setMethod('POST');
            $request->request->add([
                'contacts' => [
                    [
                        'contactName' => 'Prueba 1',
                        'phone' => '+34654789541'
                    ],
                    [
                        'contactName' => 'Prueba 2',
                        'phone' => '+34658741258'
                    ]
                ],
                'user_id' => 1,
            ]);
            $contactsController = new ContactsController();
            $contactsController->updateCreate($request);
            $this->assertTrue(true);
        }catch (\Exception $e){
            $this->assertTrue(false);
        }
    }

    public function testShowContact()
    {
        try {
            $request = new Request();
            $request->setMethod('POST');
            $request->request->add([
                'user_id' => 1,
            ]);
            $contactsController = new ContactsController();
            $contactsController->show($request);
            $this->assertTrue(true);
        }catch (\Exception $e){
            $this->assertTrue(false);
        }
    }

    public function testShowBetweenContact()
    {
        try {
            $request = new Request();
            $request->setMethod('POST');
            $request->request->add([
                'user_id_1' => 1,
                'user_id_2' => 2,
            ]);
            $contactsController = new ContactsController();
            $contactsController->show($request);
            $this->assertTrue(true);
        }catch (\Exception $e){
            $this->assertTrue(false);
        }
    }
}
