<?php

namespace Tests\Feature;

use App\Http\Controllers\ContactsController;
use Tests\TestCase;

class NeutrinoApiTest extends TestCase
{
    public function testCheckPhone()
    {
        try {
            $phone = '+34652145785';
            $contactsController = new ContactsController();
            $contactsController->checkPhone($phone);
            $this->assertTrue(true);
        }catch (\Exception $e){
            $this->assertTrue(false);
        }
    }
}
