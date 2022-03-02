<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUser extends Model
{
    protected $guarded = [];

    public function contact()
    {
        return $this->belongsToMany(Contacts::class, 'contact_users', 'id', 'contact_id');
    }
}
