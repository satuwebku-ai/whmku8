<?php

return [
    'accepted'        => ':attribute harus diterima.',
    'confirmed'       => 'Konfirmasi :attribute tidak cocok.',
    'current_password' => 'Password saat ini salah.',
    'email'           => ':attribute harus berupa alamat email yang valid.',
    'exists'          => ':attribute yang dipilih tidak valid.',
    'max'             => [
        'string'  => ':attribute tidak boleh lebih dari :max karakter.',
        'numeric' => ':attribute tidak boleh lebih dari :max.',
        'file'    => ':attribute tidak boleh lebih dari :max kilobyte.',
        'array'   => ':attribute tidak boleh lebih dari :max item.',
    ],
    'min'             => [
        'string'  => ':attribute minimal :min karakter.',
        'numeric' => ':attribute minimal :min.',
        'file'    => ':attribute minimal :min kilobyte.',
        'array'   => ':attribute minimal :min item.',
    ],
    'numeric'         => ':attribute harus berupa angka.',
    'required'        => ':attribute wajib diisi.',
    'string'          => ':attribute harus berupa teks.',
    'unique'          => ':attribute sudah digunakan.',

    // Aturan Password::min(8)->letters()->numbers()
    'password' => [
        'letters'       => ':attribute harus mengandung setidaknya satu huruf.',
        'mixed'         => ':attribute harus mengandung setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers'       => ':attribute harus mengandung setidaknya satu angka.',
        'symbols'       => ':attribute harus mengandung setidaknya satu simbol.',
        'uncompromised' => ':attribute yang diberikan muncul dalam kebocoran data. Silakan pilih :attribute yang berbeda.',
    ],

    'custom' => [],

    'attributes' => [
        'password'              => 'Password',
        'password_confirmation' => 'Konfirmasi password',
        'email'                 => 'Email',
        'name'                  => 'Nama',
    ],
];
