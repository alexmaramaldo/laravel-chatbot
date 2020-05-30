<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $table = 'transactions';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function instance()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
