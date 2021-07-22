<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LinkedInPoster implements ShouldQueue
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
        $this->content=implode("\n", $this->content);

    }
    public function handle()
    {
        
        $CLIENT_ID=json_decode($this->_SOCIAL_MEDIA_SETTINGS->linkedin,TRUE)['CLIENT_ID'];
        $CLIENT_SECRET=json_decode($this->_SOCIAL_MEDIA_SETTINGS->linkedin,TRUE)['CLIENT_SECRET'];
        $ACCESS_TOKEN=json_decode($this->_SOCIAL_MEDIA_SETTINGS->linkedin,TRUE)['ACCESS_TOKEN'];
        $PAGE_ID=json_decode($this->_SOCIAL_MEDIA_SETTINGS->linkedin,TRUE)['PAGE_ID'];


        $data= [                                   
            'author' => 'urn:li:organization:' . $PAGE_ID,
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => [          
                'com.linkedin.ugc.ShareContent' => [
                    'shareCommentary' => [
                        'text' => $this->content
                    ],
                    'shareMediaCategory' => 'NONE'
                ]
            ],
            'visibility' => [
                'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC'
            ]
        ];


        if($this->link!=null){
            
               $data= [                                   
                    'author' => 'urn:li:organization:' . $PAGE_ID,
                    'lifecycleState' => 'PUBLISHED',
                    'specificContent' => [          
                        'com.linkedin.ugc.ShareContent' => [
                            'shareCommentary' => [
                                'text' => $this->content
                            ],
                            'shareMediaCategory' => 'ARTICLE',
                            'media' => [
                                [
                                    'status' => 'READY',
                                    'originalUrl' => $this->link
                                ]
                            ]
                        ]
                    ],
                    'visibility' => [
                        'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC'
                    ]
                ];  
        }
        if($this->image!=null){
            //register image
            $image_register_data=[
               "registerUploadRequest"=>[
                  "owner"=>"urn:li:organization:".$PAGE_ID,
                  "recipes"=>[
                     "urn:li:digitalmediaRecipe:feedshare-image"
                  ],
                  "serviceRelationships"=>[
                     [
                        "identifier"=>"urn:li:userGeneratedContent",
                        "relationshipType"=>"OWNER"
                     ]
                  ],
                  "supportedUploadMechanism"=>[
                     "SYNCHRONOUS_UPLOAD"
                  ]    
               ]
            ];

            $image_register_res=\Http::withHeaders(['Authorization'=>"Bearer ".$ACCESS_TOKEN,'X-Restli-Protocol-Version'=>"2.0.0","Content-Type"=>"application/json"])->post('https://api.linkedin.com/v2/assets?action=registerUpload',$image_register_data);
            $upload_url = $image_register_res->json()['value']['uploadMechanism']["com.linkedin.digitalmedia.uploading.MediaUploadHttpRequest"]["uploadUrl"];
            $upload = \Http::withBody(
               file_get_contents($this->image) , 'image/jpeg'
            )->withHeaders(['Authorization'=>"Bearer $ACCESS_TOKEN",'X-Restli-Protocol-Version'=>"2.0.0"])->put($upload_url);
            $check= \Http::withHeaders(['Authorization'=>"Bearer ".$ACCESS_TOKEN,'X-Restli-Protocol-Version'=>"2.0.0"])->get('https://api.linkedin.com/v2/assets/'. str_replace('urn:li:digitalmediaAsset:', '', $image_register_res->json()['value']['asset']) )->json();
                $data= [                                   
                    'author' => 'urn:li:organization:' . $PAGE_ID,
                    'lifecycleState' => 'PUBLISHED',
                    'specificContent' => [          
                        'com.linkedin.ugc.ShareContent' => [
                            'shareCommentary' => [
                                'text' => $this->content
                            ],
                            'shareMediaCategory' => 'IMAGE',
                            'media' => [
                                [
                                    'media'=>$image_register_res->json()['value']['asset'],
                                    'status' => 'READY',
                                ]
                            ]
                        ]
                    ],
                    'visibility' => [
                        'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC'
                    ]
                ];
        }

 
    
        $res=\Http::withHeaders(['Authorization'=>"Bearer ".$ACCESS_TOKEN,'X-Restli-Protocol-Version'=>"2.0.0","Content-Type"=>"application/json"])->post('https://api.linkedin.com/v2/ugcPosts',$data);
        
        dd($res->json());
    }
}
