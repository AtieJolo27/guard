<?php
require __DIR__.'/../../vendor/autoload.php';
$app=require __DIR__.'/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
if (!app()->environment('testing') || !str_contains(config('database.connections.sqlite.database'), 'tmp'.DIRECTORY_SEPARATOR.'playwright')) throw new RuntimeException('Refusing to seed a non-isolated database.');
Illuminate\Support\Facades\DB::table('admins')->insert(['email'=>getenv('E2E_TEST_LOGIN'),'password'=>Illuminate\Support\Facades\Hash::make(getenv('E2E_TEST_PASSWORD'))]);
foreach(['Improper Uniform','Unbecoming Behavior','Possession of Bladed Weapons'] as $name) Illuminate\Support\Facades\DB::table('violations')->insert(['violation_name'=>$name,'violation_weight'=>$name==='Possession of Bladed Weapons'?'Major':'Minor']);
