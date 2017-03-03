<?php

namespace TCG\Voyager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Traits\Translatable;

class MenuItem extends Model
{
    use Translatable;

    protected $table = 'menu_items';

    protected $guarded = [];

    protected $translatable = ['title'];

    public function children()
    {
        return $this->hasMany('TCG\Voyager\Models\MenuItem', 'parent_id')
            ->with('children');
    }

    public function link($absolute = false)
    {
        if (!is_null($this->route)) {
            if (!Route::has($this->route)) {
                return '#';
            }

            $parameters = (array) $this->getParametersAttribute();

            return route($this->route, $parameters, $absolute);
        }

        if ($absolute) {
            return url($this->url);
        }

        return $this->url;
    }

    public function getParametersAttribute()
    {
        return json_decode($this->attributes['parameters']);
    }

    public function setParametersAttribute($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $this->attributes['parameters'] = $value;
    }
}
