<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email_address',
        'tags',
        'fields',
        'audience_id'
    ];

    protected $casts = [
        'tags' => 'array',
        'fields' => 'array'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        //'password',
        'remember_token',
    ];

    function merge_fields()
    {
        return $this->hasMany(MergeField::class); 
    }

    function setFieldsAttribute($value)
    {
        if(empty($this->attributes['fields']))
            $this->attributes['fields'] = json_encode($value);
        else
            $this->attributes['fields'] = json_encode(array_merge( json_decode($this->attributes['fields'],true), $value));
    }
}
