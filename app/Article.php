<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
	protected $table = 'articles';
	protected $fillable = ['judul','categories_id'];

    public function category()
    {
    	return $this->hasMany('App\Category','id');
    }
}
