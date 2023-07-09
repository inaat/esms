<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('roles.view')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $system_settings_id = request()->session()->get('user.system_settings_id');

            $roles = Role::where('system_settings_id', $system_settings_id)
                        ->select(['name', 'id', 'is_default', 'system_settings_id']);

            return DataTables::of($roles)
                ->addColumn('action', function ($row) {
                    if (!$row->is_default) {
                        $action = '';
                        if (auth()->user()->can('roles.update')) {
                            $action .= '<a href="' . action('RoleController@edit', [$row->id]) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> ' . __("messages.edit") . '</a>';
                        }
                        if (auth()->user()->can('roles.delete')) {
                            $action .= '&nbsp
                                <button data-href="' . action('RoleController@destroy', [$row->id]) . '" class="btn btn-xs btn-danger delete_role_button"><i class="glyphicon glyphicon-trash"></i> ' . __("messages.delete") . '</button>';
                        }
                        
                        return $action;
                    } else {
                        return '';
                    }
                })
                ->editColumn('name', function ($row) use ($system_settings_id) {
                    $role_name = str_replace('#'. $system_settings_id, '', $row->name);
                    if (in_array($role_name, ['Admin', 'Student','Teacher'])) {
                        $role_name = $role_name;
                    }
                    return $role_name;
                })
                ->removeColumn('id')
                ->removeColumn('is_default')
                ->removeColumn('system_settings_id')
                ->rawColumns([1])
                ->make(false);
        }

        return view('role.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        
        return view('role.create');

    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('roles.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $role_name = $request->input('name');
            $permissions = $request->input('permissions');
            $system_settings_id = $request->session()->get('user.system_settings_id');
            
            $count = Role::where('name', $role_name . '#' . $system_settings_id)
                        ->where('system_settings_id', $system_settings_id)
                        ->count();
            if ($count == 0) {
              
                $role = Role::create([
                            'name' => $role_name . '#' . $system_settings_id ,
                            'system_settings_id' => $system_settings_id,
                        ]);

              $this->__createPermissionIfNotExists($permissions);

                if (!empty($permissions)) {
                    $role->syncPermissions($permissions);
                }
                $output = ['success' => 1,
                            'msg' => __("english.added_success")
                        ];
            } else {
                $output = ['success' => 0,
                            'msg' => __("english.already_exists")
                        ];
            }
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' => __("english.something_went_wrong")
                        ];
        }
        return redirect('roles')->with('status', $output);
    }
 /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('roles.update')) {
            abort(403, 'Unauthorized action.');
        }

        $system_settings_id = request()->session()->get('user.system_settings_id');
        $role = Role::where('system_settings_id', $system_settings_id)
                    ->with(['permissions'])
                    ->find($id);
        $role_permissions = [];
        foreach ($role->permissions as $role_perm) {
            $role_permissions[] = $role_perm->name;
        }
        return view('role.edit')
            ->with(compact('role', 'role_permissions'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('roles.update')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $role_name = $request->input('name');
            $permissions = $request->input('permissions');
            
            $system_settings_id = $request->session()->get('user.system_settings_id');
            $count = Role::where('name', $role_name . '#' . $system_settings_id)
                        ->where('id', '!=', $id)
                        ->where('system_settings_id', $system_settings_id)
                        ->count();
            if ($count == 0) {
                $role = Role::findOrFail($id);

                if (!$role->is_default ) {

                    if (!empty($permissions)) {
                        $this->__createPermissionIfNotExists($permissions);
                        $role->syncPermissions($permissions);
                    }

                    $output = ['success' => 1,
                            'msg' => __("english.updated_success")
                        ];
                } else {
                    $output = ['success' => 0,
                            'msg' => __("english.is_default")
                        ];
                }
            } else {
                $output = ['success' => 0,
                            'msg' => __("english.already_exists")
                        ];
            }
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' => __("english.something_went_wrong")
                        ];
        }

        return redirect('roles')->with('status', $output);
    }
     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('roles.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $system_settings_id = request()->user()->system_settings_id;

                $role = Role::where('system_settings_id', $system_settings_id)->find($id);

                if (!$role->is_default || $role->name == 'Student#' . $system_settings_id) {
                    $role->delete();
                    $output = ['success' => true,
                            'msg' => __("english.deleted_success")
                            ];
                } else {
                    $output = ['success' => 0,
                            'msg' => __("english.is_default")
                        ];
                }
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
                $output = ['success' => false,
                            'msg' => __("english.something_went_wrong")
                        ];
            }

            return $output;
        }
    }
    /**
     * Creates new permission if doesn't exist
     *
     * @param  array  $permissions
     * @return void
     */
    private function __createPermissionIfNotExists($permissions)
    {
        $exising_permissions = Permission::whereIn('name', $permissions)
                                    ->pluck('name')
                                    ->toArray();

        $non_existing_permissions = array_diff($permissions, $exising_permissions);

        if (!empty($non_existing_permissions)) {
            foreach ($non_existing_permissions as $new_permission) {
                $time_stamp = \Carbon::now()->toDateTimeString();
                Permission::create([
                    'name' => $new_permission,
                    'guard_name' => 'web'
                ]);
            }
        }
    }



}
