<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'status',
        'equipment_type',
        'problem',
        'accessories',
        'maintenance',
        'action',
        'service',
        'deviceStatus',
        'parts',
        'repairedBy',
        'repairDate',
        'noted',
        'brand',
        'serialNum',
        'propertyID',
        'first_name',
        'last_name',
        'emp_number',
        'division',
        'assignDate',
        'urgent',
        'review',
        'accepted_by_user',
        'auto_close_date',
        'for_acceptance',
        'decline_reason',
        'accountableUser',
        'users',
        'custom_id',
    ];

    public function inspector()
{
    return $this->belongsTo(User::class, 'repairedBy', 'emp_number');
}

public function endUser()
{
    return $this->belongsTo(User::class, 'emp_number', 'emp_number');
}


}

