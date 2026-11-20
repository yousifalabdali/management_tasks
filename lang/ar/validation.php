<?php

// رسائل التحقق بالعربية (الأكثر استخدامًا في التطبيق)
return [
    'accepted' => 'يجب قبول :attribute.',
    'confirmed' => 'تأكيد :attribute غير متطابق.',
    'current_password' => 'كلمة المرور غير صحيحة.',
    'date' => ':attribute ليس تاريخًا صحيحًا.',
    'email' => 'يجب أن يكون :attribute بريدًا إلكترونيًا صحيحًا.',
    'enum' => 'قيمة :attribute غير صالحة.',
    'exists' => 'قيمة :attribute غير موجودة.',
    'in' => 'قيمة :attribute غير صالحة.',
    'integer' => 'يجب أن يكون :attribute عددًا صحيحًا.',
    'lowercase' => 'يجب أن يكون :attribute بأحرف صغيرة.',
    'max' => ['string' => 'يجب ألا يتجاوز :attribute :max حرفًا.', 'numeric' => 'يجب ألا يتجاوز :attribute :max.', 'array' => 'يجب ألا يحتوي :attribute على أكثر من :max عنصر.', 'file' => 'يجب ألا يتجاوز :attribute :max كيلوبايت.'],
    'min' => ['string' => 'يجب ألا يقل :attribute عن :min أحرف.', 'numeric' => 'يجب ألا يقل :attribute عن :min.', 'array' => 'يجب أن يحتوي :attribute على :min عناصر على الأقل.', 'file' => 'يجب ألا يقل :attribute عن :min كيلوبايت.'],
    'password' => [
        'letters' => 'يجب أن تحتوي :attribute على حرف واحد على الأقل.',
        'mixed' => 'يجب أن تحتوي :attribute على حروف كبيرة وصغيرة.',
        'numbers' => 'يجب أن تحتوي :attribute على رقم واحد على الأقل.',
        'symbols' => 'يجب أن تحتوي :attribute على رمز واحد على الأقل.',
        'uncompromised' => ':attribute ظهرت في تسريب بيانات، اختر غيرها.',
    ],
    'regex' => 'صيغة :attribute غير صحيحة.',
    'required' => 'حقل :attribute مطلوب.',
    'string' => 'يجب أن يكون :attribute نصًا.',
    'unique' => ':attribute مستخدم من قبل.',

    'attributes' => [
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'current_password' => 'كلمة المرور الحالية',
    ],
];
