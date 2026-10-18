<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoanType extends Model
{
    protected $fillable = ['name', 'interest_rate'];

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}