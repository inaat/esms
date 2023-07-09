<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use App\Models\Campus;
class User extends Authenticatable
{
    use HasApiTokens,Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function employee()
    {
        return $this->belongsTo(\App\Models\HumanRM\HrmEmployee::class, 'hook_id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'hook_id');
    }
    
    public function teacher() {
        return $this->belongsTo(\App\Models\HumanRM\HrmEmployee::class, 'hook_id');
    }
    public function get_roles(){
        $roles = [];
        foreach ($this->getRoleNames() as $key => $role) {
            $roles[$key] = $role;
        }

        return $roles;
    }
    public function getRoleNameAttribute()
    {
        $role_name_array = $this->getRoleNames();
        $role_name = !empty($role_name_array[0]) ? explode('#', $role_name_array[0])[0] : '';
        return $role_name;
    }
        /**
     * Gives campuss permitted for the logged in user
     *
     * @param: int $system_settings_id
     * @return string or array
     */
    public function permitted_campuses($system_settings_id = null)
    {
        $user = $this;
        //$is_admin = $user->hasAnyPermission('Admin#' . $user->system_settings_id);
        if ($user->can('access_all_campuses')  ) {
            return 'all';  
        } else {
            $system_settings_id = !is_null($system_settings_id) ? $system_settings_id : request()->session()->get('user.system_settings_id');
            $permitted_campuses = [];
            $all_campuses = Campus::where('system_settings_id', $system_settings_id)->get();
            //foreach ($all_campuses as $campus) {
               // if ($user->can('campus.' . $campus->id)) {
                    //$permitted_campuses[] = $campus->id;
                    $permitted_campuses[] = auth()->user()->campus_id;
               // }
           // }
            return $permitted_campuses;
        }
    }
       /**
     * Returns if a user can access the input campus
     *
     * @param: int $campus_id
     * @return boolean
     */
    public static function can_access_this_campus($campus_id, $system_settings_id = null)
    {
        $permitted_campuses= auth()->user()->permitted_campuses($system_settings_id);
        
        if ($permitted_campuses == 'all' || in_array($campus_id, $permitted_campuses)) {
            return true;
        }

        return false;
    }

    public function scopeOnlyPermittedCampuses($query)
    {
        $user = auth()->user();
        $permitted_campuses = $user->permitted_campuses();
        $is_admin = $user->hasAnyPermission('Admin#' . $user->system_settings_id);
        if ($permitted_campuses != 'all' && !$user->can('superadmin') && !$is_admin) {
            $permissions = ['access_all_campuses'];
            foreach ($permitted_campuses as $campus_id) {
                $permissions[] = 'campus.' . $campus_id;
            }

            return $query->whereHas('permissions', function($q) use ($permissions) {
                $q->whereIn('permissions.name', $permissions);
            });

        } else {
            return $query;
        }
    }
       //Getter Attributes
       public function getImageAttribute($value) {
        return url($value);
    }
}
