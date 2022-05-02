<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Time extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'users_id',
        'title',
        'description',
        'time',
    ];

    protected $casts = [
        'created_at' => "datetime:Y-m-d H:i:s",
        'updated_at' => "datetime:Y-m-d H:i:s",
    ];

    protected $appends = [
        'converted_time'
    ];

    public function getConvertedTimeAttribute()
    {
        return \Carbon\CarbonInterval::seconds($this->time)->cascade()->forHumans();
    }

   /*  public function getCreatedAtAttribute($date)
    {
        return Carbon::parse('Y-m-d H:i:s', $date)->format('Y-m-d');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse('Y-m-d H:i:s', $date)->format('Y-m-d');
    }*/
}
