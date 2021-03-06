<?php

class Module extends Eloquent {

    protected $table = 'module';

    public function SubModule() {

        return $this->hasMany('SubModule', 'module_id');
    }

    public static function hasAccess($id) {
        if ($id) {
            $user = User::find(Auth::user()->id);
            if ($user) {
                $module = self::find($id);
                if ($module) {
                    $submodules = SubModule::where('module_id', $id)->get();
                    if ($submodules) {
                        foreach ($submodules as $submodule) {
                            $access = AccessGroup::where('role_id', $user->role)->where('submodule_id', $submodule->id)->where('access_permission', 1)->first();

                            if ($access) {
                                return true;
                            }
                        }
                    }
                }
            }
        }

        return false;
    }

}
