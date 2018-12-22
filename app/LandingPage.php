<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    protected $table = 'landing_pages';

    protected $guarded = [];

    /**
     * Get the program that owns the landing page.
     */
    public function program()
    {
        return $this->belongsTo('App\Program');
    }
}
