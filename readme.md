# Push To Social [ Facebook , Twitter , Telegram , Linkedin ]

via this package you can push notifications to [ Facebook , Twitter , Telegram , Linkedin ] 
- content 
- image ( Optional )
- link ( Optional )

```
 
	(new SocialHelper($platforms=[],$content=[],$image='',$link=''))->push()

```
# How To Push

```

	( new SocialHelper(
		['facebook','twitter','telegram','linkedin'],
		['Hello', 'Iam here','Message From Push to social'],
		'https://nafezly.com/site_images/title.png',
		'https://nafezly.com/'
	) )->push();

```
![SocialHelper](https://github.com/peter-tharwat/push-to-social/blob/master/images/screenshot.png)

# You have to install

```

	composer require abraham/twitteroauth
	composer require facebook/graph-sdk
	composer require laravel-notification-channels/telegram

```

# Migrations for laravel

```

	Schema::create('social_media_settings', function (Blueprint $table) {
        $table->bigIncrements('id');

        $table->text('facebook')->nullable();
        $table->text('twitter')->nullable();
        $table->text('linkedin')->nullable(); 
        $table->text('telegram')->nullable(); 

        $table->text('publish_settings')->nullable();

        $table->timestamps();
    });

```
- Create Jobs folder inside app folder
- Move all Jobs in the repo to Jobs Folder
- Create Notifications Folder inside app folder For telegram
- Move TeleNotification To Notifications folder
- Move Routes , Models and Controllers To Your Project


# Authorize Facebook And Linkedin 

# Seed Database

-facebook

```

	{
	   "FB_ACCESS_TOKEN":"",
	   "APP_ID":"",
	   "CLIENT_SECRET":"",
	   "PAGE_ID":"",
	   "REDIRECT_URL":"",
	   "PAGE_ACCESS_TOKEN":""
	}

```
-twitter

```

	{
	   "API_KEY":"",
	   "API_SECRET_KEY":"",
	   "BEARER_TOKEN":"",
	   "ACCESS_TOKEN":"",
	   "ACCESS_TOKEN_SECRET":""
	}

```
-linkedin

```

	{
	   "CLIENT_ID":"",
	   "CLIENT_SECRET":"",
	   "REDIRECT_URL":"",
	   "SCOPES":"r_emailaddress,r_basicprofile,w_member_social,w_organization_social,rw_organization_admin,rw_ads",
	   "CODE":"",
	   "ACCESS_TOKEN":"",
	   "REFRESH_ACCESS_TOKEN":"",
	   "ACCESS_TOKEN_EXPIRATION_DATE":"",
	   "PAGE_ID":""
	}

```
-telegram

```

	{
	   "TELEGRAM_BOT_TOKEN":""
	}

```
