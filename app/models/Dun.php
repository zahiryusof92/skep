<?php

class Dun extends Eloquent
{
    protected $table = 'dun';

    public function scopeSelf()
    {
        return self::where('is_active', true)->where('is_deleted', false);
    }

    public static function getOptions()
    {
        $items = self::orderBy('description', 'asc')->where('is_deleted', 0)->get();

        $model = [
            '' => trans('- Select Dun -')
        ];
        $model += array_pluck($items, 'description', 'description');

        return $model;
    }

    public static function getData($request = [])
    {
        $query = self::join('parliment', 'dun.parliament', '=', 'parliment.id')
            ->where('parliment.is_deleted', 0)
            ->where('parliment.is_active', 1)
            ->where('dun.is_deleted', 0);
        if (!empty($request['parliment'])) {
            $query = $query->where('parliment.id', $request['parliment']);
        }
        $items = $query->selectRaw('parliment.description as parliment, dun.description as name, dun.code, dun.is_active,
                    dun.created_at')
            ->get();
        return $items;
    }
}
