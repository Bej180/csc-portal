@props(['user', 'placeholder'])

@php

    $image = 'images/avatar-u.png';
    if ($user) {
        $placeholder ??= 'image';
            $role = $user->role;
            $gender = $user->gender;



            $image = match (true) {
                !(!$user->image) => 'storage/'.$user->image,
                !!$user->$placeholder =>  $user->$placeholder,
                $gender === 'FEMALE' => 'images/avatar-f.png',
                $gender === 'MALE' => 'images/avatar-m.png',
                default => 'images/avatar-u.png',
            };
        
    }
@endphp

<img src="{{ asset($image) }}" {{ $attributes }} />
