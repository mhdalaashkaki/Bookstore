<?php

return [
    'accepted' => ':attribute kabul edilmelidir.',
    'active_url' => ':attribute geçerli bir URL değil.',
    'after' => ':attribute, :date tarihinden sonra bir tarih olmalıdır.',
    'alpha' => ':attribute yalnızca harflerden oluşmalıdır.',
    'alpha_dash' => ':attribute yalnızca harfler, rakamlar, tireler ve alt çizgiler içerebilir.',
    'alpha_num' => ':attribute yalnızca harfler ve rakamlar içerebilir.',
    'array' => ':attribute bir dizi olmalıdır.',
    'before' => ':attribute, :date tarihinden önce bir tarih olmalıdır.',
    'between' => [
        'numeric' => ':attribute, :min ile :max arasında olmalıdır.',
        'file' => ':attribute, :min ile :max kilobayt arasında olmalıdır.',
        'string' => ':attribute, :min ile :max karakter arasında olmalıdır.',
        'array' => ':attribute, :min ile :max öğe arasında olmalıdır.',
    ],
    'confirmed' => ':attribute onayı eşleşmiyor.',
    'email' => ':attribute geçerli bir e-posta adresi olmalıdır.',
    'max' => [
        'numeric' => ':attribute, :max değerinden büyük olamaz.',
        'file' => ':attribute, :max kilobayttan büyük olamaz.',
        'string' => ':attribute, :max karakterden uzun olamaz.',
        'array' => ':attribute, :max öğeden fazla olamaz.',
    ],
    'min' => [
        'numeric' => ':attribute en az :min olmalıdır.',
        'file' => ':attribute en az :min kilobayt olmalıdır.',
        'string' => ':attribute en az :min karakter olmalıdır.',
        'array' => ':attribute en az :min öğe içermelidir.',
    ],
    'required' => ':attribute alanı gereklidir.',
    'unique' => ':attribute zaten kullanılmaktadır.',
    'attributes' => [
        'email' => 'E-posta',
        'username' => 'Kullanıcı Adı',
        'password' => 'Şifre',
        'name' => 'İsim',
        'product_name' => 'Ürün Adı',
        'description' => 'Açıklama',
        'price' => 'Fiyat',
        'stock' => 'Stok',
    ],
];
