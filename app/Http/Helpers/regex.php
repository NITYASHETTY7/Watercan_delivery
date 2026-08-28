<?php

/**
 *
 * @category ZStarter
 *
 * @ref     Book My Water product
 * @author  <Book My Water info@bookmywater.come>
 * @license <https://watercane-dev.dze-labs.in Book My Water>
 * @version <zStarter: 202402-V2.0>
 * @link    <https://watercane-dev.dze-labs.in>
 */


function regex($key, $filter = 0)
{
    $regex_patterns = [
        'name' => [
            'pattern' => "^[a-zA-Z]+(?:[ .'-][a-zA-Z]+)*$",
            'message' => 'Please enter a valid name (only alphabets and spaces allowed)',
        ],

        'phone_number' => [
            'pattern' => '^[0-9]{10,20}$',
            'message' => 'Phone should contain only digits and be 10–20 characters long.',
        ],

        'text' => [
            'pattern' => '.*',
            'message' => 'Please enter a valid name (only alphabets and spaces allowed)',
        ],
        'textarea' => [
            'pattern' => '.*',
            'message' => 'Please enter a valid value',
        ],
        'email' => [
            'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$',
            'message' => 'Please enter a valid email address',
        ],
        'tel_no' => [
            'pattern' => '^\+?\(?\d{1,3}\)?[\d\s()-]{7,15}\d$',
            'message' => 'Please enter a valid phone number',
        ],

        'gst_number' => [
            'pattern' => '^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][0-9A-Z]Z[0-9A-Z]$',
            'message' => 'Enter a valid GST Number (example: 27ABCDE1234F2Z5).',
        ],

        'date' => [
            'pattern' => '\d{4}-\d{2}-\d{2}',
            'message' => 'Please enter a valid date in YYYY-MM-DD format',
        ],
        'password' => [
            'pattern' => '.*',
            'message' => 'Please ensure your password contains at least one digit, one lowercase letter, one uppercase letter, one special character (@, $, &, or !), and is at least 8 characters long.',
        ],
        'url' => [
            'pattern' => 'https?:\/\/(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,6}(?:\/[^\s]*)?',
            'message' => 'Please enter a valid URL',
        ],
        'username' => [
            'pattern' => '[a-zA-Z0-9_.]{3,20}',
            'message' => 'Username must be between 3 and 20 characters long and contain only alphanumeric characters and underscores',
        ],
        'ip_address' => [
            'pattern' => '((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$|^([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}$|^([0-9a-fA-F]{1,4}:){1,7}:?$|^([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}$|^([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}$|^([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}$|^([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}$|^([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}$|^[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})',
            'message' => 'Please enter a valid IP address',
        ],
        'hex_color' => [
            'pattern' => '#?([a-f0-9]{6}|[a-f0-9]{3})i',
            'message' => 'Please enter a valid hexadecimal color code',
        ],
        'percentage' => [
            'pattern' => '\d*\.?\d+',
            'message' => 'Please enter a valid non-negative number, including integers or decimals.',
        ],
        'flag_emoji_code' => [
            'pattern' => 'U\\+1F1[E6-F][0-9A-F]\\sU\\+1F1[E6-F][0-9A-F]',
            'message' => 'Please enter a valid flag emoji Unicode code (e.g., U+1F1E8 U+1F1FC).',
        ],
        'credit_card' => [
            'pattern' => '((4\d{3})|(5[1-5]\d{2})|(6011))[- ]?\d{4}[- ]?\d{4}[- ]?\d{4}|3[4,7][\d]{13}',
            'message' => 'Please enter a valid credit card number',
        ],
        'pin_code' => [
            'pattern' => '[A-Za-z0-9]+',
            'message' => 'Please enter a valid PIN code.',
        ],
        'gst' => [
            'pattern' => '^[A-Za-z\d]{2,15}$',
            'message' => 'Please enter a valid GST (Goods and Services Tax) number',
        ],
        'pan' => [
            'pattern' => '^[A-Za-z0-9-]{8,15}$',
            'message' => 'Please enter a valid PAN (Permanent Account Number)',
        ],
        'tan' => [
            'pattern' => '([A-Z]{4}[0-9]{5}[A-Z]{1})|([0-9]{2}-[0-9]{7})|([0-9]{9})|([A-Z]{2}[0-9]{8})',
            'message' => 'Please enter a valid TAN (Tax Deduction and Collection Account Number)',
        ],
        'driving_license' => [
            'pattern' => '^[A-Z0-9]{1,15}([- ]?[A-Z0-9]{1,15}){0,4}$',
            'message' => 'Please enter a valid Indian Driving Licence number',
        ],
        'age' => [
            'pattern' => '^(0?[1-9]|[1-9][0-9]|1[01][0-9]|100)$',
            'message' => 'Please enter a valid age (18 years and above)',
        ],
        'dob' => [
            'pattern' => '^((19[0-9]{2}|20[0-9]{2})[-\/](0[1-9]|1[0-2])[-\/](0[1-9]|[12][0-9]|3[01])|' . // YYYY-MM-DD or YYYY/MM/DD
                '(0[1-9]|[12][0-9]|3[01])[-\/](0[1-9]|1[0-2])[-\/](19[0-9]{2}|20[0-9]{2})|' . // DD-MM-YYYY or DD/MM/YYYY
                '(0[1-9]|1[0-2])[-\/](0[1-9]|[12][0-9]|3[01])[-\/](19[0-9]{2}|20[0-9]{2}))$', // MM-DD-YYYY or MM/DD/YYYY
            'message' => 'Please enter a valid date of birth in any common format (YYYY-MM-DD, DD-MM-YYYY, MM-DD-YYYY)',
        ],
        'address' => [
            'pattern' => '^[A-Za-z0-9\s,.\-]+$',
            'message' => 'Please enter a valid address.',
        ],
        'country_code' => [
            'pattern' => "^(\+|\b00)[1-9]\d{0,2}$",
            'message' => 'Please enter a valid Country Phone code.',
        ],
        'code' => [
            'pattern' => '[a-z\_]+',
            'message' => 'Please enter a valid code consisting of small letters and hyphens only.',
        ],
        'amount' => [
            'pattern' => '\d+(\.\d{1,2})?e?',
            'message' => 'Please enter a valid code consisting of letters, digits, dots, and an optional letter "e".',
        ],
        'positive_number' => [
            'pattern' => '\d*\.?\d+',
            'message' => 'Please enter a positive numbers.',
        ],
        'alpha_numeric' => [
            'pattern' => '[A-Za-z0-9]+',
            'message' => 'Please enter a positive numbers.',
        ],
        'account_number' => [
            'pattern' => '^[A-Z0-9\- ]{5,34}$',
            'message' => 'Please input min 10 digit and only numbers',
        ],
        'currency_name' => [
            'pattern' => '[A-Z]{3}',
            'message' => 'Please input exactly three uppercase letters.'
        ],
        'geo_coordinates' => [
            'pattern' => '^[-+]?\d{1,2}(\.\d+)?,\s*[-+]?\d{1,3}(\.\d+)?$',
            'message' => 'Please enter valid geo coordinates in the format "latitude, longitude" (e.g., 12.345678, 76.543210).'
        ],
        'aadhaar_number' => [
            'pattern' => '^\d{12}$',
            'message' => 'Please input exactly 12 digits for the Aadhaar number.'
        ],

    ];

    if (isset($regex_patterns[$key])) {
        if ($filter == 0) {
            return $regex_patterns[$key];
        } else {
            $regex_patterns[$key]['pattern'] = '/^' . $regex_patterns[$key]['pattern'] . '$/';
            return $regex_patterns[$key];
        }
    } else {
        return null; // or handle the case where the key is not found
    }
}

function validation($key, $filter = 0)
{
    $validate_patterns = [

        'common_name' => [
            'pattern' => ['minlength' => 1, 'maxlength' => 200, 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 1 characters and a maximum of 200 characters.',
        ],

        'common_phone_number' => [
            'pattern' => ['minlength' => 10, 'maxlength' => 20,'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 9 characters and a maximum of 12 characters.',
        ],

        'phone_number' => [
            'pattern' => ['minlength' => 10, 'maxlength' => 20, 'mandatory' => 'required', 'only_digits' => true ],
            'message' => 'Phone should contain only digits and be 10–20 characters long.',
        ],

        'common_email' => [
            'pattern' => ['minlength' => 1, 'maxlength' => 100, 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 10 characters and a maximum of 30 characters.',
        ],

        'common_short_description' => [
            'pattern' => ['minlength' => 1, 'maxlength' => 255, 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 40 characters and a maximum of 90 characters.',
        ],

        'common_description' => [
            'pattern' => ['minlength' => 1, 'maxlength' => 2000, 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 40 characters and a maximum of 90 characters.',
        ],

        'common_meta_title' => [
            'pattern' => ['minlength' => 1, 'maxlength' => 90, 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 5 characters and a maximum of 90 characters.',
        ],

        'common_meta_description' => [
            'pattern' => ['minlength' => 1, 'maxlength' => 90, 'mandatory' => ''],
            'message' => 'Please make your input consists of a minimum of 40 characters and a maximum of 90 characters.',
        ],

        'common_meta_keywords' => [
            'pattern' => ['maxlength' => 90, 'mandatory' => ''],
            'message' => 'Please make your input consists of a minimum of 40 characters and a maximum of 90 characters.',
        ],

        'common_title' => [
            'pattern' => ['minlength' => 1, 'maxlength' => 500, 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 5 characters and a maximum of 90 characters.',
        ],

        'common_amount' => [
            'pattern' => ['minlength' => 0, 'maxlength' => 10,  'mandatory' => 'required'],
            'message' => 'Please enter a valid amount. The amount should consist of digits, optionally separated by commas for thousands, and may contain a decimal point for fractional amounts.',
        ],
        'common_address' => [
            'pattern' => ['minlength' => 1, 'maxlength' => 150, 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 10 characters and a maximum of 30 characters.',
        ],

        'common_pin_code' => [
            'pattern' => ['minlength' => 1, 'maxlength' => 6, 'mandatory' => ''],
            'message' => 'Please make your input consists of a minimum of 10 characters and a maximum of 30 characters.',
        ],

        'common_code' => [
            'pattern' => ['minlength' => 1, 'maxlength' => 30, 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 1 characters and a maximum of 30 characters.',
        ],

        'common_dob' => [
            'pattern' => ['maxlength' => 90, 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 40 characters and a maximum of 90 characters.',
        ],

        'common_password' => [
            'pattern' => ['minlength' => 6, 'maxlength' => 90, 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 40 characters and a maximum of 90 characters.',
        ],

        'gender' => [
            'pattern' => ['mandatory' => 'required'],
            'message' => 'Please fill this.',

        ],
        'common_quantity' => [
            'pattern' => ['mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 40 characters and a maximum of 90 characters.',
        ],

        'common_percentage' => [
            'pattern' => ['minlength' => 0, 'maxlength' => 100, 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 40 characters and a maximum of 90 characters.',
        ],

        'common_time' => [
            'pattern' => ['minlength' => 0, 'maxlength' => 60, 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 40 characters and a maximum of 90 characters.',
        ],

        'common_standard' => [
            'pattern' => ['minlength' => 0, 'maxlength' => 1000, 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 1000 characters and a maximum of 90 characters.',
        ],

        'blog_image' => [
            'pattern' => ['max_file_size' => '2', 'allowed_extensions' => 'image/jpg,image/png,image/jpeg', 'dimension' => '1200*70'],
        ],
        'default_image' => [
            'pattern' => ['max_file_size' => '2', 'allowed_extensions' => 'image/jpg,image/png,image/jpeg', 'dimension' => '1200*70'],
        ],
        'excel' => [
            'pattern' => ['max_file_size' => '2048',  'allowed_extensions' => 'xls,xlsx',],
        ],

        'template_subject' => [
            'pattern' => ['minlength' => 10, 'maxlength' => 90, 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 10 characters and a maximum of 90 characters.',
        ],

        'faq_question' => [
            'pattern' => ['minlength' => '10', 'maxlength' => '40', 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 10 characters and a maximum of 40 characters.',
        ],

        'order_qty' => [
            'pattern' =>  ['mandatory' => 'required', 'minlength' => '0', 'maxlength' => '1000'],
        ],

        'order_discount' => [
            'pattern' =>  ['mandatory' => 'required', 'minlength' => '0', 'maxlength' => '8000'],
        ],

        'order_shipping_charge' => [
            'pattern' => ['mandatory' => 'required', 'minlength' => '0', 'maxlength' => '5000'],
        ],

        'item_sku' => [
            'pattern' => ['minlength' => '1', 'maxlength' => '100', 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 1 characters and a maximum of 100 characters.',
        ],

        'item_tax' => [
            'pattern' => ['minlength' => '1', 'maxlength' => '100', 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 10 characters and a maximum of 40 characters.',
        ],
        'subscription_duration' => [
            'pattern' => ['mandatory' => 'required', 'minlength' => '0', 'maxlength' => '1000'],
        ],

        'subscriptions_price' => [
            'pattern' => ['mandatory' => 'required', 'minlength' => '0', 'maxlength' => '1000'],
        ],

        'promo_code' => [
            'pattern' => ['minlength' => '1', 'maxlength' => '40', 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 1 characters and a maximum of 40 characters.',
        ],

        'promo_code_max_uses' => [
            'pattern' => ['minlength' => '0', 'maxlength' => '10', 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 1 number and a maximum of 40 numbers.',
        ],

        'promo_code_value' => [
            'pattern' => ['minlength' => '0', 'maxlength' => '10', 'mandatory' => 'required'],
            'message' => 'Please make your input consists of a minimum of 1 number and a maximum of 40 numbers.',
        ],
        'common_tel_code' => [
            'minlength' => '1',
            'maxlength' => '15',
            'mandatory' => 'required'
        ],
        'bank_ifsc_code' => [
            'pattern' => ['minlength' => '0', 'maxlength' => '11', 'mandatory' => 'required'],
        ],

        'bank_account_number' => [
            'pattern' => ['minlength' => '10', 'mandatory' => 'required'],
            'message' =>  'Please make your input consists of a minimum of 1 number and a maximum of 40 numbers.',
        ],
        'gst_number' => [
            'pattern' => ['minlength' => 15,'maxlength' => 15,'mandatory' => 'required'],
            'message' => 'GST number must be exactly 15 characters and valid.',
        ],

        'empty' => [
            'pattern' => ['mandatory' => ''],
        ],
        'required' => [
            'pattern' => ['mandatory' => 'required'],
        ],
    ];

    if (isset($validate_patterns[$key])) {
        if ($filter == 0) {
            return $validate_patterns[$key];
        } else {
            $validate_patterns[$key]['pattern'] = $validate_patterns[$key]['pattern'];
            return $validate_patterns[$key];
        }
    } else {
        return null; // or handle the case where the key is not found
    }
}
