<?php

namespace App\Providers;

use App\Support\AdminInsights;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        RateLimiter::for('login', function (Request $request) {
            $login = $request->input('email');
            $key = hash('sha256', is_string($login) ? strtolower(trim($login)) : 'invalid');

            return [
                Limit::perMinute(20)->by('login-ip:'.$request->ip()),
                Limit::perMinute(5)->by('login:'.$request->ip().':'.$key),
            ];
        });
        RateLimiter::for('student-lookup', fn (Request $request) => Limit::perMinute(30)->by($request->session()->get('svrms_role').':'.$request->session()->get('svrms_id').':'.$request->ip()));
        RateLimiter::for('case-write', fn (Request $request) => Limit::perMinute(15)->by($request->session()->get('svrms_role').':'.$request->session()->get('svrms_id').':'.$request->ip()));
        View::composer('admin.insights-menu', function ($view) {
            if (session('svrms_role') === 'admin') {
                $view->with(AdminInsights::data());
            }
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
