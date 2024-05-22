<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('student', function () {
            return "<?php if(auth()->check() && auth()->user()->is_student()): ?>";
        });

        Blade::directive('admin', function () {
            return "<?php if(auth()->check() && auth()->user()->is_admin()): ?>";
        });

        Blade::directive('advisor', function () {
            return "<?php if(auth()->check() && auth()->user()->is_advisor()): ?>";
        });
        Blade::directive('staff', function () {
            return "<?php if(auth()->check() && auth()->user()->is_staff()): ?>";
        });


        Validator::extend('admission_format', function ($attribute, $value, $parameters, $validator) {
            // Check if the format is correct
            if (!preg_match('/^\d{4}\/\d{4}$/', $value)) {
                return false;
            }
    
            // Extract admission year and graduation year
            [$admissionYear, $graduationYear] = explode('/', $value);
    
            // Check if years have a 5-year interval
            if (($graduationYear - $admissionYear) !== 5) {
                return false;
            }
    
            return true;
        });
    
        // Custom error message for the rule
        Validator::replacer('admission_format', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, 'The :attribute must be in the format "YYYY/YYYY" with a 5-year interval.');
        });

       
    }
}
