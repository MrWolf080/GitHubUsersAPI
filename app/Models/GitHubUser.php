<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GitHubUser extends Model
{
    use HasFactory;

    protected $table='git_hub_users';
    public $incrementing=false;
    protected $fillable=[
        'id',
        'type',
        'login',
        'avatar_url',
        'html_url'
    ];

    public $timestamps=false;
}
