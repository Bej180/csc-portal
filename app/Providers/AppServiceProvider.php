<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Hash;

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
        ini_set('max_execution_time', 300);

        Blade::directive('role', function ($role) {
            return "<?php if(auth()->check() && auth()->user()->role == '{$role}'): ?>";
        });
        Blade::directive('elserole', function ($role) {
            return "<?php elseif(auth()->check() && auth()->user()->role == '{$role}'): ?>";
        });
        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('staff', function (?string $designation = null) {
            if ($designation) {
                return "<?php if(auth()->check() && auth()->user()->is_staff() && auth()->user()->profile()->designation === '{$designation}'): ?>";
            }
            return "<?php if(auth()->check() && auth()->user()->is_staff()): ?>";
        });
        Blade::directive('elsestaff', function (?string $designation = null) {
            if ($designation) {
                return "<?php elseif(auth()->check() && auth()->user()->is_staff() && auth()->user()->profile()->designation === '{$designation}'): ?>";
            }
            return "<?php elseif(auth()->check() && auth()->user()->is_staff()): ?>";
        });

        Blade::directive('endstaff', function () {
            return "<?php endif; ?>";
        });



        # hod 
        Blade::directive('hod', function () {
            return "<?php if(auth()->check() && auth()->user()->staff->is_hod): ?>";
        });
        Blade::directive('elsehod', function () {
            return "<?php elseif(auth()->check() && auth()->user()->staff->is_hod): ?>";
        });
        Blade::directive('endhod', function () {
            return "<?php endif; ?>";
        });




        Blade::directive('student', function () {
            return "<?php if(auth()->check() && auth()->user()->is_student()): ?>";
        });
        Blade::directive('elsestudent', function () {
            return "<?php elseif(auth()->check() && auth()->user()->is_student()): ?>";
        });
        Blade::directive('endstudent', function () {
            return "<?php endif; ?>";
        });


        Blade::directive('admin', function () {
            return "<?php if(auth()->check() && auth()->user()->is_admin()): ?>";
        });
        Blade::directive('elseadmin', function () {
            return "<?php elseif(auth()->check() && auth()->user()->is_admin()): ?>";
        });
        Blade::directive('endadmin', function () {
            return "<?php endif; ?>";
        });



        Blade::directive('advisor', function () {
            return "<?php if(auth()->check() && auth()->user()->is_advisor()): ?>";
        });
        Blade::directive('elseadvisor', function () {
            return "<?php elseif(auth()->check() && auth()->user()->is_advisor()): ?>";
        });
        Blade::directive('endadvisor', function () {
            return "<?php endif; ?>";
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







        



        # PIN
        Validator::extend('pin', function ($attribute, $value, $parameters, $validator) {
            if (count($parameters)) {
                $instances = [
                    'auth' => auth(),
                    'request' => request(),
                ];
                $type = $parameters[0] ?? 'request';
                if (!array_key_exists($type, $instances)) {
                    $type = 'request';
                }
                $user = $instances[$type]->user();
            } else {
                $user = request()->user();
            }

            return Hash::check($value, $user->pin);
        });
        Validator::replacer('pin', function ($message, $attribute, $rule, $parameters) {
            return $message ?? 'PIN is invalid';
        });



         # PASSWORD
        Validator::extend('password', function ($attribute, $value, $parameters, $validator) {
           
            if (count($parameters)) {
                $instances = [
                    'auth' => auth(),
                    'request' => request(),
                ];
                $type = $parameters[0] ?? 'request';
                if (!array_key_exists($type, $instances)) {
                    $type = 'request';
                }
                $user = $instances[$type]->user();
            }
            else {
                $user = request()->user();
            }


            return Hash::check($value, $user->password);
        });
        Validator::replacer('password', function ($message, $attribute, $rule, $parameters) {
            return $message ?? 'Passwords did not match';
        });









        # SESSION
        Validator::extend('session', function ($attribute, $value, $parameters, $validator) {
            // Check if the format is correct
            if (!preg_match('/^\d{4}\/\d{4}$/', $value)) {
                return false;
            }
            $limit = (int) ($parameters[0] ?? 1); // Default to 1 if no parameter is provided

            // Extract admission year and graduation year
            [$admissionYear, $graduationYear] = explode('/', $value);

            return ($graduationYear - $admissionYear) !== $limit;
        });

        Validator::replacer('session', function ($message, $attribute, $rule, $parameters) {
            $parameters[0] ??= 1;
            return str_replace([
                ':attribute',
                ':parameter'
            ], [
                $attribute,
                $parameters[0]
            ], 'The :attribute must be in the format "YYYY/YYYY" with a :parameter-year interval.');
        });
    }
}
