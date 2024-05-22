<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

if (!function_exists('toTitleCase')) {
  function toTitleCase($string)
  {
      return ucwords(strtolower($string));
  }
}

if (!function_exists('toCamelCase')) {
  function toCamelCase($string)
  {
      $string = ucwords(str_replace(['-', '_'], ' ', $string));
      return lcfirst(str_replace(' ', '', $string));
  }
}

if (!function_exists('error_helper')) {
  function error_helper($message, array $merge = [], int $statusCode = 400) {
    $silent = request()->get('silent');
    $holder =  $silent ? 'message' : (is_string($message) ? 'error' : 'errors');

    response()->json(array_merge([$holder => $message], $merge), $statusCode);

  }
}

if (!function_exists('toSnakeCase')) {
  function toSnakeCase($string)
  {
      return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1_', $string));
  }
}

if (!function_exists('formatFileSize')) {
  function formatFileSize($bytes)
  {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    $index = 0;
    while ($bytes >= 1024 && $index < count($units) - 1) {
      $bytes /= 1024;
      $index++;
    }

    return round($bytes, 2) . ' ' . $units[$index];
  }
}

if (!function_exists('invitation_url')) {
  function invitation_url($token)
  {
    if (!$token) {
      return null;
    }

    return url('/register?invite=' . $token);
  }
}

if (!function_exists('generateInt')) {
  function generateInt(int $length = 6): string
  {
    $bytes = random_bytes($length);
    return str_pad(substr(bin2hex($bytes), 0, $length * 2), $length, '0', STR_PAD_LEFT);
  }
}

if (!function_exists('Email')) {
  function Email(Mailable $mailable, User|string $user = null)
  {
    $user ??= auth()?->user();
    if (!is_string($user)) {
    }
    if (!$user) {
      return null;
    }
    try {

      $email = is_string($user) ? $user : $user->email;
      return Mail::to($email)->send($mailable);
    } catch (Exception $e) {
      return null;
    }
  }
}

if (!function_exists('generateToken')) {
  function generateToken(?string $tableColumn = null, int $length = 60, ?int $expiresAt = null): ?string
  {
    if (!$tableColumn) {
      return Str::random($length);
    }

    $table = explode('.', $tableColumn)[0] ?? '';
    $column = explode('.', $tableColumn)[1] ?? '';

    do {
      $token = Str::random($length);
      $exists = DB::table($table)->where($column, $token)->exists();
    } while ($exists);

    try {
      // Attempt to cache the token
      Cache::put($token, true, $expiresAt ?? 60); // Cache for 1 minute
    } catch (Exception $e) {
    }

    return $token;
  }
}

if (!function_exists('mask_email')) {
  function mask_email($email)
  {
    // Split the email address into username and domain parts
    list($username, $domain) = explode('@', $email);

    // Determine the length of the username and how much of it to mask
    $usernameLength = strlen($username);
    $maskedLength = max(ceil($usernameLength / 2), 3); // Mask at least 3 characters or half of the username, whichever is greater

    // Mask the username
    $maskedUsername = substr($username, 0, $maskedLength) . str_repeat('*', $usernameLength - $maskedLength);

    // Reassemble the masked email address
    return $maskedUsername . '@' . $domain;
  }
}



if (!function_exists('timeago')) {

  function timeago($dateTime)
  {
    $time = Carbon::parse($dateTime);
    $now = Carbon::now();

    $diffInSeconds = $now->diffInSeconds($time);
    $diffInMinutes = $now->diffInMinutes($time);
    $diffInHours = $now->diffInHours($time);


    return match (true) {
      $diffInSeconds < 1 => 'Just now',
      $diffInSeconds < 60 => $diffInSeconds . ' ' . str_plural('sec', $diffInSeconds) . ' ago',
      $diffInMinutes < 60 => $diffInMinutes . ' ' . str_plural('min', $diffInMinutes) . ' ago',
      $diffInHours == 1 => 'an hour ago',
      $diffInHours < 5 => $diffInHours . ' hours ago',
      $time->isSameDay($now) => 'Today at ' . $time->format('h:iA'),
      $time->isYesterday() => 'Yesterday at ' . $time->format('h:iA'),
      default => $time->format('Y-m-d \a\t h:iA'),
    };
  }
}

if (!function_exists('error_page')) {
  function error_page()
  {
    return [
      '400' => [
        'title' => "Bad Request",
        'description' => "Sorry! The <em>:domain</em> server could not understand the request due to invalid syntax.",
        'icon' => "fa fa-ban red",
        'buttons' => [
          [
            'name' => 'Goto Dashboard',
            'link_to' => 'dashboard',
            'material' => 'house'
          ],
          [
            'name' => "Go back",
            'link_to' => "previous",
            'material' => "arrow_back"
          ]

        ],
        'button' => [
          'name' => "Back to previous page",
          'link_to' => "previous",
          'material' => "arrow_back"
        ],
        'why' => [
          'title' => "What happened?",
          'description' => "A 400 error status indicates that the server could not understand the request due to invalid syntax."
        ],
        'what_do' => [
          'title' => "What can I do?",
          'visitor' => [
            'title' => "If you're a site visitor",
            'description' => "Please use your browsers back button and check that you fill the form correctly. If you need immediate assistance, please send us an email instead."
          ],
          'owner' => [
            'title' => "If you're the site owner",
            'description' => "Please check that you everything right, or get in touch with your website provider if you believe this to be an error."
          ],
        ],
      ],
      '401' => [
        'title' => "Unauthorized",
        'description' => "Oops! You need be authenticated to access this resource on <em>:domain</em>.",
        'buttons' => [
          [
            'name' => 'Goto Dashboard',
            'link_to' => 'dashboard',
            'material' => 'house'
          ],
          [
            'name' => 'Refresh',
            'link_to' => 'refresh',
            'material' => 'refresh'
          ],
          [
            'name' => "Go back",
            'link_to' => "previous",
            'material' => "arrow_back"
          ]

        ],
        'button' => [
          'name' => "Refresh",
          'link_to' => "reload",
          'material' => "refresh"
        ]
      ],
      '403' => [
        'title' => "Forbidden",
        'description' => "Sorry! You don't have access permissions for that on <em>:domain</em>.",
        'buttons' => [

          [
            'name' => "Go back",
            'link_to' => "previous",
            'material' => "arrow_back"
          ],
          [
            'name' => 'Goto Dashboard',
            'link_to' => 'dashboard',
            'material' => 'house'
          ],

        ],
        'button' => [
          'name' => "Take Me To The Dashboard",
          'link_to' => "home",
          'material' => "house"
        ],
      ],
      '404' => [
        'title' => "Not Found",
        'description' => "We couldn't find what you're looking for on <em>:domain</em>.",
        'buttons' => [

          [
            'name' => "Goto back",
            'link_to' => "previous",
            'material' => "arrow_back"
          ],
          [
            'name' => 'Goto Dashboard',
            'link_to' => 'dashboard',
            'material' => 'house'
          ],

        ],
        'button' => [
          'name' => "Take Me To The Dashboard",
          'link_to' => "home",
          'material' => "house"
        ],
        [
          'name' => 'Goto Dashboard',
          'link_to' => 'dashboard',
          'material' => 'house'
        ],
      ],
      '405' => [
        'title' => "Method not allowed",
        'description' => "You requested this page with an invalid or nonexistent HTTP method on <em>:domain</em>.",
        'buttons' => [
          [
            'name' => 'Goto Dashboard',
            'link_to' => 'dashboard',
            'material' => 'house'
          ],
          [
            'name' => "Go back",
            'link_to' => "previous",
            'material' => "arrow_back"
          ]

        ],
        'button' => [
          'name' => "Back to previous page",
          'link_to' => "previous",
          'material' => "arrow_back"

        ],
        'why' => [
          'title' => "What happened?",
          'description' => "A 405 error status indicates that the request method is known by the server but has been disabled and cannot be used."
        ],
        'what_do' => [
          'title' => "What can I do?",
          'visitor' => [
            'title' => "If you're a site visitor",
            'description' => "Go to previous page and retry. If you need immediate assistance, please send us an email instead."
          ],
          'owner' => [
            'title' => "If you're the site owner",
            'description' => "Go to previous page and retry. If the error persists get in touch with your website provider if you believe this to be an error."
          ],
        ],
      ],


      '408' => [
        'title' => "Request Timeout",
        'description' => "The server <em>:domain</em> would like to shut down this unused connection.",
        'buttons' => [

          [
            'name' => 'Refresh',
            'link_to' => 'refresh',
            'material' => 'refresh'
          ],
          [
            'name' => "Go back",
            'link_to' => "previous",
            'material' => "arrow_back"
          ],
          [
            'name' => 'Goto Dashboard',
            'link_to' => 'dashboard',
            'material' => 'house'
          ],

        ],
        'button' => [
          'name' => "Refresh",
          'link_to' => "reload",
          'material' => "refresh",
        ],
      ],
      '419' => [
        'title' => 'Authentication Timeout',
        'description' => "<em>:domain</em> has expired due to inactivity.",
        'buttons' => [
          [
            'name' => 'Goto Dashboard',
            'link_to' => 'dashboard',
            'material' => 'house'
          ],
          [
            'name' => "Go back",
            'link_to' => "previous",
            'material' => "arrow_back"
          ]

        ],
        'button' => [
          'name' => "Take Me To The Dashboard",
          'link_to' => "home",
          'material' => "house"
        ],

      ],
      '429' => [
        'title' => 'Too Many Requests',
        'description' => "The web server is returning a rate limiting notification for <em>:domain</em>.",
        'icon' => "fa fa-dashboard red",
        'buttons' => [

          [
            'name' => 'Refresh',
            'link_to' => 'refresh',
            'material' => 'refresh'
          ],
          [
            'name' => "Go back",
            'link_to' => "previous",
            'material' => "arrow_back"
          ],
          [
            'name' => 'Goto Dashboard',
            'link_to' => 'dashboard',
            'material' => 'house'
          ],

        ],
        'button' => [
          'name' => "Refresh",
          'link_to' => "reload",
          'material' => "refresh"
        ],
        'why' => [
          'title' => "What happened?",
          'description' => "This error means you have exceeded the request rate limit for the the web server you are accessing.</p><p class=\"lead\">Rate Limit Thresholds are set higher than a human browsing this site should be able to reach and mostly for protection against automated requests and attacks."
        ],
        'what_do' => [
          'title' => "What can I do?",
          'visitor' => [
            'title' => "If you're a site visitor",
            'description' => "The best thing to do is to slow down with your requests and try again in a few minutes. We apologize for any inconvenience."
          ],
          'owner' => [
            'title' => "If you're the site owner",
            'description' => "This error is mostly likely very brief, the best thing to do is to check back in a few minutes and everything will probably be working normal again. If the error persists, contact your website host."
          ],
        ],
      ],
      '500' => [
        'title' => "Internal Server Error",
        'description' => "The web server is returning an internal error for <em>:domain</em>.",
        'buttons' => [
          [
            'name' => 'Goto Dashboard',
            'link_to' => 'dashboard',
            'material' => 'house'
          ],
          [
            'name' => 'Refresh',
            'link_to' => 'refresh',
            'material' => 'refresh'
          ],
          [
            'name' => "Go back",
            'link_to' => "previous",
            'material' => "arrow_back"
          ]

        ],
        'button' => [
          'name' => "Refresh",
          'link_to' => "reload",
          'material' => "refresh"
        ],
      ],
      '502' => [
        'title' => "Bad Gateway",
        'description' => "The web server is returning an unexpected networking error for <em>:domain</em>.",
        'buttons' => [
          [
            'name' => 'Goto Dashboard',
            'link_to' => 'dashboard',
            'material' => 'house'
          ],
          [
            'name' => 'Refresh',
            'link_to' => 'refresh',
            'material' => 'refresh'
          ],
          [
            'name' => "Go back",
            'link_to' => "previous",
            'material' => "arrow_back"
          ]

        ],
        'button' => [
          'name' => "Refresh",
          'link_to' => "reload",
          'material' => "refresh"
        ],
      ],
      '503' => [
        'title' => "Service Unavailable",
        'description' => "The web server is returning an unexpected temporary error for <em>:domain</em>.",
        'buttons' => [
          [
            'name' => 'Goto Dashboard',
            'link_to' => 'dashboard',
            'material' => 'house'
          ],
          [
            'name' => 'Refresh',
            'link_to' => 'refresh',
            'material' => 'refresh'
          ],
          [
            'name' => "Go back",
            'link_to' => "previous",
            'material' => "arrow_back"
          ]

        ],
        'button' => [
          'name' => "Refresh",
          'link_to' => "reload",
          'material' => "refresh"
        ],
      ],
      '504' => [
        'title' => "Gateway Timeout",
        'description' => "The web server is returning an unexpected networking error for <em>:domain</em>.",
        'buttons' => [
          [
            'name' => 'Goto Dashboard',
            'link_to' => 'dashboard',
            'material' => 'house'
          ],
          [
            'name' => 'Refresh',
            'link_to' => 'refresh',
            'material' => 'refresh'
          ],
          [
            'name' => "Go back",
            'link_to' => "previous",
            'material' => "arrow_back"
          ]

        ],
        'button' => [
          'name' => "Refresh",
          'link_to' => "reload",
          'material' => "refresh"
        ],
      ],
      'maintenance' => [
        'title' => "Temporary Maintenance",
        'description' => "The web server for <em>:domain</em> is currently undergoing some maintenance.",
        'buttons' => [
          [
            'name' => 'Goto Dashboard',
            'link_to' => 'dashboard',
            'material' => 'house'
          ],
          [
            'name' => 'Refresh',
            'link_to' => 'refresh',
            'material' => 'refresh'
          ],
          [
            'name' => "Go back",
            'link_to' => "previous",
            'material' => "arrow_back"
          ]

        ],
        'button' => [
          'name' => "Refresh",
          'link_to' => "reload",
          'material' => "refresh"
        ],
      ],
    ];
  }
}
