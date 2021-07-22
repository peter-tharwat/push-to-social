<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TelegramPosterJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $timeout = 10;
    public $content; 
    public $image;
    public $link; 
    public $_SOCIAL_MEDIA_SETTINGS;

    public function __construct($content =[], $image = null, $link=null){
        $this->content=$content; 
        $this->image=$image;
        $this->link=$link; 
        $this->_SOCIAL_MEDIA_SETTINGS=\App\SocialMediaSetting::first();

        if($this->link!=null)
            array_push($this->content,$this->link);
        if($this->image=="NO")
            $this->image=null;
        elseif($this->image=="DEFAULT")
            $this->image="https://nafezly.com/site_images/title.png?v=1";
        //$this->content=implode("\n", $this->content);

    }
    public function handle()
    {
       \Notification::route('telegram', 'nafezly')
        ->notify(
            new \App\Notifications\TeleNotification(
                $this->content,  
                $this->image,
                'nafezly',
                true

            )
        );
    }
}
