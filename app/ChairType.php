<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChairType extends Model
{
    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');

    }

    public function updatedBy()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function account()
    {
        return $this->hasOne(Account::class, 'id', 'account_id');
    }
}
