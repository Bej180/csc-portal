@props(['user', 'placeholder'])

@php

    $image = 'images/avatar-u.png';
    if ($user) {
        $placeholder ??= 'image';
        $gender = $user->gender;

        // check if has role
        if ($user->role) {
            $role = $user->role;
            $profile = $user->$role;
            $gender = $profile->gender;
        }
        else {
            $profile = $user;
        }



            $image = match (true) {
                !(!$profile->image) => 'storage/'.$profile->image,
                !!$user->$placeholder =>  $user->$placeholder,
                $gender === 'female' => 'images/avatar-f.png',
                $gender === 'male' => 'images/avatar-m.png',
                default => 'images/avatar-u.png',
            };
        
    }
@endphp

<img src="{{ asset($image) }}" {{ $attributes }} />
