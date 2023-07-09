<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseCategory extends Model
{
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

 

  
    public static function forDropdown($show_none = false)
    {

        $expense_categories=ExpenseCategory::orderBy('id', 'asc')
                    ->pluck('name', 'id');

        if ($show_none) {
            $categories->prepend(__('lang.none'), '');
        }

        return $expense_categories;
    }
}
