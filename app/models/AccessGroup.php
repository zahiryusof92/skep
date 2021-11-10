<?php

class AccessGroup extends Eloquent {

    protected $table = 'access_group';

    public function submodule() {

        return $this->belongsTo('SubModule', 'submodule_id');
    }

    public static function getAccessPermission($user_id) {
        $user = User::find($user_id);
        $user_role = $user->role;

        $permissions = self::where('role_id', $user_role)->orderBy('submodule_id', 'asc')->get();

        return $permissions;
    }

    public static function getAccessPermissionByModule($user_id, $module_id) {
        $user = User::find($user_id);
        $user_role = $user->role;

        $permissions = self::where('role_id', $user_role)->where('submodule_id', $module_id)->first();

        return $permissions;
    }

    public static function hasAccessModule($module_name) {
        $permissions = false;

        $user = User::find(Auth::user()->id);
        if ($user) {
            $submodule = SubModule::where('name_en', $module_name)->first();
            if($submodule) {
                $permissions = self::where('role_id', $user->role)->where('submodule_id', $submodule->id)->where('access_permission', 1)->count();
            }
        }

        return $permissions;
    }

    public static function hasAccess($module_id) {
        $permissions = false;

        $user = User::find(Auth::user()->id);
        if ($user) {
            $permissions = self::where('role_id', $user->role)->where('submodule_id', $module_id)->where('access_permission', 1)->count();
        }

        return $permissions;
    }

    public static function hasInsert($module_id) {
        $permissions = false;

        $user = User::find(Auth::user()->id);
        if ($user) {
            $permissions = self::where('role_id', $user->role)->where('submodule_id', $module_id)->where('insert_permission', 1)->count();
        }

        return $permissions;
    }

    public static function hasUpdate($module_id) {
        $permissions = false;

        $user = User::find(Auth::user()->id);
        if ($user) {
            $permissions = self::where('role_id', $user->role)->where('submodule_id', $module_id)->where('update_permission', 1)->count();
        }

        return $permissions;
    }

}
