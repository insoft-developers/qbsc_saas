<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute harus diterima.',
    'accepted_if' => ':attribute harus diterima ketika :other bernilai :value.',
    'active_url' => ':attribute harus berupa URL yang valid.',
    'after' => ':attribute harus berupa tanggal setelah :date.',
    'after_or_equal' => ':attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha' => ':attribute hanya boleh berisi huruf.',
    'alpha_dash' => ':attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num' => ':attribute hanya boleh berisi huruf dan angka.',
    'ascii' => ':attribute hanya boleh berisi karakter dan simbol alfanumerik satu byte.',
    'before' => ':attribute harus berupa tanggal sebelum :date.',
    'before_or_equal' => ':attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between' => [
        'array' => ':attribute harus memiliki antara :min dan :max item.',
        'file' => 'Ukuran file :attribute harus antara :min dan :max kilobyte.',
        'numeric' => ':attribute harus antara :min dan :max.',
        'string' => ':attribute harus memiliki panjang antara :min dan :max karakter.',
    ],
    'boolean' => ':attribute harus bernilai true atau false.',
    'can' => ':attribute memiliki nilai yang tidak diizinkan.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'current_password' => 'Password salah.',
    'date' => ':attribute harus berupa tanggal yang valid.',
    'date_equals' => ':attribute harus berupa tanggal yang sama dengan :date.',
    'date_format' => ':attribute harus sesuai dengan format :format.',
    'decimal' => ':attribute harus memiliki :decimal angka di belakang koma.',
    'declined' => ':attribute harus ditolak.',
    'declined_if' => ':attribute harus ditolak ketika :other bernilai :value.',
    'different' => ':attribute dan :other harus berbeda.',
    'digits' => ':attribute harus terdiri dari :digits digit.',
    'digits_between' => ':attribute harus antara :min dan :max digit.',
    'dimensions' => ':attribute memiliki dimensi gambar yang tidak valid.',
    'distinct' => ':attribute memiliki nilai duplikat.',
    'doesnt_end_with' => ':attribute tidak boleh diakhiri dengan salah satu dari: :values.',
    'doesnt_start_with' => ':attribute tidak boleh diawali dengan salah satu dari: :values.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'ends_with' => ':attribute harus diakhiri dengan salah satu dari: :values.',
    'enum' => ':attribute yang dipilih tidak valid.',
    'exists' => ':attribute yang dipilih tidak valid.',
    'extensions' => ':attribute harus memiliki salah satu ekstensi berikut: :values.',
    'file' => ':attribute harus berupa file.',
    'filled' => ':attribute harus memiliki nilai.',
    'gt' => [
        'array' => ':attribute harus memiliki lebih dari :value item.',
        'file' => 'Ukuran file :attribute harus lebih besar dari :value kilobyte.',
        'numeric' => ':attribute harus lebih besar dari :value.',
        'string' => ':attribute harus lebih panjang dari :value karakter.',
    ],
    'gte' => [
        'array' => ':attribute harus memiliki minimal :value item.',
        'file' => 'Ukuran file :attribute harus lebih besar atau sama dengan :value kilobyte.',
        'numeric' => ':attribute harus lebih besar atau sama dengan :value.',
        'string' => ':attribute harus lebih panjang atau sama dengan :value karakter.',
    ],
    'hex_color' => ':attribute harus berupa warna heksadesimal yang valid.',
    'image' => ':attribute harus berupa gambar.',
    'in' => ':attribute yang dipilih tidak valid.',
    'in_array' => ':attribute harus ada di dalam :other.',
    'integer' => ':attribute harus berupa bilangan bulat.',
    'ip' => ':attribute harus berupa alamat IP yang valid.',
    'ipv4' => ':attribute harus berupa alamat IPv4 yang valid.',
    'ipv6' => ':attribute harus berupa alamat IPv6 yang valid.',
    'json' => ':attribute harus berupa string JSON yang valid.',
    'lowercase' => ':attribute harus dalam huruf kecil.',
    'lt' => [
        'array' => ':attribute harus memiliki kurang dari :value item.',
        'file' => 'Ukuran file :attribute harus kurang dari :value kilobyte.',
        'numeric' => ':attribute harus kurang dari :value.',
        'string' => ':attribute harus kurang dari :value karakter.',
    ],
    'lte' => [
        'array' => ':attribute tidak boleh memiliki lebih dari :value item.',
        'file' => 'Ukuran file :attribute harus kurang dari atau sama dengan :value kilobyte.',
        'numeric' => ':attribute harus kurang dari atau sama dengan :value.',
        'string' => ':attribute harus kurang dari atau sama dengan :value karakter.',
    ],
    'mac_address' => ':attribute harus berupa alamat MAC yang valid.',
    'max' => [
        'array' => ':attribute tidak boleh memiliki lebih dari :max item.',
        'file' => 'Ukuran file :attribute tidak boleh lebih dari :max kilobyte.',
        'numeric' => ':attribute tidak boleh lebih dari :max.',
        'string' => ':attribute tidak boleh lebih dari :max karakter.',
    ],
    'max_digits' => ':attribute tidak boleh memiliki lebih dari :max digit.',
    'mimes' => ':attribute harus berupa file dengan tipe: :values.',
    'mimetypes' => ':attribute harus berupa file dengan tipe: :values.',
    'min' => [
        'array' => ':attribute harus memiliki minimal :min item.',
        'file' => 'Ukuran file :attribute minimal :min kilobyte.',
        'numeric' => ':attribute minimal bernilai :min.',
        'string' => ':attribute minimal terdiri dari :min karakter.',
    ],
    'min_digits' => ':attribute harus memiliki minimal :min digit.',
    'missing' => ':attribute harus tidak ada.',
    'missing_if' => ':attribute harus tidak ada ketika :other bernilai :value.',
    'missing_unless' => ':attribute harus tidak ada kecuali :other bernilai :value.',
    'missing_with' => ':attribute harus tidak ada ketika :values ada.',
    'missing_with_all' => ':attribute harus tidak ada ketika semua :values ada.',
    'multiple_of' => ':attribute harus kelipatan dari :value.',
    'not_in' => ':attribute yang dipilih tidak valid.',
    'not_regex' => 'Format :attribute tidak valid.',
    'numeric' => ':attribute harus berupa angka.',
    'password' => [
        'letters' => ':attribute harus mengandung setidaknya satu huruf.',
        'mixed' => ':attribute harus mengandung setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers' => ':attribute harus mengandung setidaknya satu angka.',
        'symbols' => ':attribute harus mengandung setidaknya satu simbol.',
        'uncompromised' => ':attribute yang diberikan telah muncul dalam kebocoran data. Silakan gunakan :attribute lain.',
    ],
    'present' => ':attribute harus ada.',
    'present_if' => ':attribute harus ada ketika :other bernilai :value.',
    'present_unless' => ':attribute harus ada kecuali :other bernilai :value.',
    'present_with' => ':attribute harus ada ketika :values ada.',
    'present_with_all' => ':attribute harus ada ketika semua :values ada.',
    'prohibited' => ':attribute tidak diizinkan.',
    'prohibited_if' => ':attribute tidak diizinkan ketika :other bernilai :value.',
    'prohibited_unless' => ':attribute tidak diizinkan kecuali :other berada di :values.',
    'prohibits' => ':attribute melarang :other untuk ada.',
    'regex' => 'Format :attribute tidak valid.',
    'required' => ':attribute wajib diisi.',
    'required_array_keys' => ':attribute harus memiliki entri untuk: :values.',
    'required_if' => ':attribute wajib diisi ketika :other bernilai :value.',
    'required_if_accepted' => ':attribute wajib diisi ketika :other diterima.',
    'required_unless' => ':attribute wajib diisi kecuali :other ada di :values.',
    'required_with' => ':attribute wajib diisi ketika :values ada.',
    'required_with_all' => ':attribute wajib diisi ketika semua :values ada.',
    'required_without' => ':attribute wajib diisi ketika :values tidak ada.',
    'required_without_all' => ':attribute wajib diisi ketika tidak ada satu pun dari :values yang ada.',
    'same' => ':attribute harus sama dengan :other.',
    'size' => [
        'array' => ':attribute harus mengandung :size item.',
        'file' => 'Ukuran file :attribute harus :size kilobyte.',
        'numeric' => ':attribute harus berukuran :size.',
        'string' => ':attribute harus terdiri dari :size karakter.',
    ],
    'starts_with' => ':attribute harus diawali dengan salah satu dari: :values.',
    'string' => ':attribute harus berupa teks.',
    'timezone' => ':attribute harus berupa zona waktu yang valid.',
    'unique' => ':attribute sudah digunakan.',
    'uploaded' => ':attribute gagal diunggah.',
    'uppercase' => ':attribute harus dalam huruf besar.',
    'url' => ':attribute harus berupa URL yang valid.',
    'ulid' => ':attribute harus berupa ULID yang valid.',
    'uuid' => ':attribute harus berupa UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],
];
