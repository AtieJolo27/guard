<?php
require __DIR__.'/../vendor/autoload.php';
$app=require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo json_encode(['mailer'=>config('mail.default'),'smtp_host_set'=>(bool)config('mail.mailers.smtp.host'),'smtp_username_set'=>(bool)config('mail.mailers.smtp.username'),'smtp_password_set'=>(bool)config('mail.mailers.smtp.password'),'from_address_set'=>(bool)config('mail.from.address')]);
