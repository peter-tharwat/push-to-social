<?php 
namespace App\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Abraham\TwitterOAuth\TwitterOAuth;
class SocialHelper 
{
    public $platforms;
    public $content;
    public $image;
    public $link;


	public function __construct($platforms=null,$content=null,$image="DEFAULT",$link=null)
    {
         $this->platforms=$platforms;
         $this->content=$content;
         $this->image=$image;
         $this->link=$link;
         //$this->inline_content=implode( " ", $content );
         $this->_SOCIAL_MEDIA_SETTINGS=\App\SocialMediaSetting::first();
    }
    public function push(){
        if( in_array("facebook",$this->platforms)){
            $this->to_facebook();
        }if( in_array("twitter",$this->platforms)){
            $this->to_twitter();
        }if( in_array("linkedin",$this->platforms)){
            $this->to_linkedin();
        }if( in_array("telegram",$this->platforms)){
            $this->to_telegram();
        }
    }
    public function to_telegram(){
       \App\Jobs\TelegramPosterJob::dispatch($this->content,$this->image,$this->link);
    }
    public function to_facebook(){
        \App\Jobs\FacebookPosterJob::dispatch($this->content,$this->image,$this->link);
    }
    public function to_twitter(){
        \App\Jobs\TwitterPosterJob::dispatch($this->content,$this->image,$this->link);
    }
    public function to_linkedin(){
        \App\Jobs\LinkedInPoster::dispatch($this->content,$this->image,$this->link);
    }
 
}