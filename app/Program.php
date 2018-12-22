<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'programs';

    protected $guarded = [];

    public $timestamps = false;

    /**
     * Get the partner that owns the program.
     */
    public function partner()
    {
        return $this->belongsTo('App\Partner');
    }


    /**
     * Get the landing page assets for this program.
     */
    public function landingPages()
    {
        return $this->hasMany('App\LandingPage');
    }
}
