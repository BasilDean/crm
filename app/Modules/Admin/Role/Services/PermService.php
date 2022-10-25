<?php

namespace App\Modules\Admin\Role\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PermService
{
    public function save(Request $request, Model $model)
    {
//        $model->fill($request->only($model->getFillable()));
//        $model->save();

        return true;
    }
}
