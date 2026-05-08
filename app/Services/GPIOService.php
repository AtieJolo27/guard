<?php

namespace App\Services;

class GPIOService
{
    /**
     * Create a new class instance.
     */
    // public static function on(int $pin): void
    // {
    //     exec("sudo pkill gpioset");
    //     exec("gpioset --daemonize --hold-period 20ms GPIO{$pin}=1 ");
    //     //exec("gpioset -c gpiochip0 {$pin}=1");
    // }
    // public static function off(int $pin): void
    // {    exec("sudo pkill gpioset");
    //      exec("gpioset --daemonize --hold-period 20ms GPIO{$pin}=0 ");
    //     //exec("gpioset -c gpiochip0 {$pin}=0");
    // }
    public static function move(int $pin, int $angle): void
    {
        $angle = max(0, min(180, $angle));
        $script = base_path("scripts/servo.py"); 
        exec("python3 {$script} {$pin} {$angle} > /dev/null 2>&1 &");

    }
}
