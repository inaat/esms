<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    use HasFactory;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public static function forDropdown($show_none = false,$check_permission = true)
    {
        $query=Campus::orderBy('campus_name', 'asc');
                   // ->pluck('campus_name', 'id');
        if ($check_permission) {
            $permitted_campuses = auth()->user()->permitted_campuses();
            if ($permitted_campuses != 'all') {
                $query->whereIn('id', $permitted_campuses);
            }
        }
        $result = $query->get();

        $campuses = $result->pluck('campus_name', 'id');
        if ($show_none) {
            $campuses->prepend(__('lang.none'), '');
        }

        return $campuses;
    }
}
