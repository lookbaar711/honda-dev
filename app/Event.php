<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $table = 'events';
    protected $primaryKey = 'id';

    

    //protected $fillable = ['created_at'];

    //protected $dates =['created_at','updated_at'];

    //protected $guarded = ['id'];
}
