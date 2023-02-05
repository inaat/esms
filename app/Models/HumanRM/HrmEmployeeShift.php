<?php

namespace App\Models\HumanRM;

use Illuminate\Database\Eloquent\Model;

class HrmEmployeeShift extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function employee()
    {
        return $this->belongsTo(HrmEmployee::class);
    }
}
