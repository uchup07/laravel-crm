<?php

namespace VentureDrake\LaravelCrm\Models;


class Invite extends Model
{

    protected $guarded = ['id'];

    protected $fillable=[
        'external_id',
        'email',
        'token'
      ];

    public function getTable()
    {
        return config('laravel-crm.db_table_prefix').'invites';
    }

}