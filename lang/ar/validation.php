<?php

/**
 * Arabic messages for the validation rules used by this application.
 * Any rule not listed here falls back to Laravel's built-in English message.
 */
return [
    'before' => 'يجب أن يكون :attribute تاريخًا سابقًا لـ :date.',
    'before_or_equal' => 'يجب أن يكون :attribute تاريخًا سابقًا لـ :date أو مساويًا له.',
    'date' => 'حقل :attribute ليس تاريخًا صحيحًا.',
    'digits_between' => 'يجب أن يحتوي :attribute على عدد أرقام بين :min و :max.',
    'email' => 'يجب أن يكون :attribute بريدًا إلكترونيًا صحيحًا.',
    'enum' => 'القيمة المختارة في :attribute غير صحيحة.',
    'exists' => 'القيمة المختارة في :attribute غير موجودة.',
    'max' => [
        'numeric' => 'يجب ألا يكون :attribute أكبر من :max.',
        'string' => 'يجب ألا يزيد :attribute عن :max حرفًا.',
    ],
    'min' => [
        'numeric' => 'يجب ألا يكون :attribute أصغر من :min.',
        'string' => 'يجب ألا يقل :attribute عن :min حرفًا.',
    ],
    'numeric' => 'يجب أن يكون :attribute رقمًا.',
    'required' => 'حقل :attribute مطلوب.',
    'string' => 'يجب أن يكون :attribute نصًا.',
    'unique' => 'قيمة :attribute مستخدمة من قبل.',
];
