<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $table = 'partners';
    
    protected $guarded = [];
    
    public $timestamps = false;

    /**
     * Get the programs for the partner.
     */
    public function programs()
    {
        return $this->hasMany('App\Program');
    }
}
