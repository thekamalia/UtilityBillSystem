<?php

namespace App\Models;

use App\Models\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_name',
        'month',
        'building_type',
        'usability',
        'bill',
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'name');
    }
}
