@echo off
echo Fixing services.php...
echo.

cd /d C:\xampp\htdocs\ummahtravel

REM Backup old file
if exist config\services.php.bak del config\services.php.bak
copy config\services.php config\services.php.bak

REM Create correct services.php
echo ^<?php > config\services.php
echo. >> config\services.php
echo return [ >> config\services.php
echo. >> config\services.php
echo     'midtrans' =^> [ >> config\services.php
echo         'server_key' =^> env('MIDTRANS_SERVER_KEY'), >> config\services.php
echo         'client_key' =^> env('MIDTRANS_CLIENT_KEY'), >> config\services.php
echo         'is_production' =^> env('MIDTRANS_IS_PRODUCTION', false), >> config\services.php
echo         'merchant_id' =^> env('MIDTRANS_MERCHANT_ID'), >> config\services.php
echo     ], >> config\services.php
echo. >> config\services.php
echo     'postmark' =^> [ >> config\services.php
echo         'token' =^> env('POSTMARK_TOKEN'), >> config\services.php
echo     ], >> config\services.php
echo. >> config\services.php
echo     'resend' =^> [ >> config\services.php
echo         'key' =^> env('RESEND_KEY'), >> config\services.php
echo     ], >> config\services.php
echo. >> config\services.php
echo     'ses' =^> [ >> config\services.php
echo         'key' =^> env('AWS_ACCESS_KEY_ID'), >> config\services.php
echo         'secret' =^> env('AWS_SECRET_ACCESS_KEY'), >> config\services.php
echo         'region' =^> env('AWS_DEFAULT_REGION', 'us-east-1'), >> config\services.php
echo     ], >> config\services.php
echo. >> config\services.php
echo     'slack' =^> [ >> config\services.php
echo         'notifications' =^> [ >> config\services.php
echo             'bot_user_oauth_token' =^> env('SLACK_BOT_USER_OAUTH_TOKEN'), >> config\services.php
echo             'channel' =^> env('SLACK_BOT_USER_DEFAULT_CHANNEL'), >> config\services.php
echo         ], >> config\services.php
echo     ], >> config\services.php
echo ]; >> config\services.php

echo Services.php has been fixed!
echo.

pause