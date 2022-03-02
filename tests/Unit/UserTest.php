<?php

namespace Tests\Feature;

use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testCreateNewUser()
    {
        try {
            $request = new Request();
            $request->setMethod('POST');
            $request->request->add([
                'name' => 'Javi Prueba',
                'lastName' => 'Manga Prueba',
                'phone' => '+34658745896'
            ]);
            $userController = new UsersController();
            $userController->create($request);
            $this->assertTrue(true);
        }catch (\Exception $e){
            $this->assertTrue(false);
        }
    }
}
