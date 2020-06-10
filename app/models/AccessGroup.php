<?php

class AccessGroup extends Eloquent {
    protected $table = 'access_group';
    
    public static function getAccessPermission($user_id) {
        $user = User::find($user_id);
        $user_role = $user->role;

        $permissions = self::where('role_id', $user_role)->get();
        return $permissions;
    }
    
    public static function getAccessPermissionByModule($user_id, $module_id) {
        $user = User::find($user_id);
        $user_role = $user->role;

        $permissions = self::where('role_id', $user_role)->where('submodule_id', $module_id)->first();
        return $permissions;
    }
}