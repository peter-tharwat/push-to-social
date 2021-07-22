<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaSetting extends Model
{
    use HasFactory;
    /*protected $casts = [
        'facebook'=>'array',
        'twitter'=>'array',
        'instagram'=>'array',
        'linkedin'=>'array',
        'whatsapp'=>'array',
        'telegram'=>'array',
        'google'=>'array',
        'publish_settings'=>'array'
    ];*/
    protected $table="social_media_settings";
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
