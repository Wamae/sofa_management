<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chair extends Model
{
    public function chairTypeId()
    {
        return $this->hasOne(ChairType::class, 'id', 'chair_type_id');
    }

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
