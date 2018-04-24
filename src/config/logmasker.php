<?php
return [
    'mask_all' => [
        'replacer' => '*',
        'fields' => [
            'cvc',
            'm_pin',
            'mpin2',
            'mpin',
            'cvv',
            'cvv2',
            'wpin',
            'wpin_confirmation',
            'aavcode',
            'password',
            'new_password_confirmation',
            'new_password',
            'password_confirmation',
            'm_pin_confirmation'
        ],
    ],
    'mask_partial' => [
        'replacer' => '******',
        'start' => 6,
        'length' => 6,
        'fields' => [
            'card_no',
            'cleartext_card_no',
            'pan',
            'accountnumber'
        ]
    ]
];
