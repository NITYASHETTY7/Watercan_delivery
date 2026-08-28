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

 namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class UsersImport implements ToCollection, WithHeadingRow
{

    public $importData = [];

    public function collection(Collection $rows)
    {
        $batchSize = 500;
        $batches = $rows->chunk($batchSize);
        if ($rows->isEmpty()) {
            $this->importData['errors'] = [
                'success' => false,
                'message' => __('ui.blank_excel_msg'),
            ];
            return;
        }
        try {
            
            $minlength = validation('phone_number',1)['pattern']['minlength'];
            $maxlength = validation('phone_number',1)['pattern']['maxlength'];
            foreach ($batches as $batch) {
                foreach ($batch as $index => $row) {
                    if (isset($row['first_name']) && $row['first_name'] == null) {
                        throw ValidationException::withMessages(['First Name can not be null at row:'.$index+2]);
                    }
                    if ($row['last_name'] == null) {
                        throw ValidationException::withMessages(['Last Name can not be null at row:'.$index+2]);
                    }
                    if ($row['email'] == null) {
                        throw ValidationException::withMessages(['Email can not be null at row:'.$index+2]);
                    }
                    if ($row['phone'] == null) {
                        throw ValidationException::withMessages(['Phone can not be null at row:'.$index+2]);
                    }
                    if ($row['gender'] == null) {
                        throw ValidationException::withMessages(['Gender can not be null at row:'.$index+2]);
                    }
                    if ($row['dob'] == null) {
                        throw ValidationException::withMessages(['DOB can not be null at row:'.$index+2]);
                    }
                   
                    if ($row['gender'] != 'male' && $row['gender'] != 'female') {
                        throw ValidationException::withMessages(['Gender must be male or female at row: ' . ($index + 2)]);
                    }

                    if (strlen($row['phone']) < $minlength || strlen($row['phone']) > $maxlength) {
                        throw ValidationException::withMessages(['Phone must be numeric and between 10 to 15 digits at row: ' . ($index + 2)]);
                    }

                    if (isset($row['id'])) {
                        $phone_already_exists = User::where('phone', $row['phone'])
                            ->where('id', '!=', $row['id'])
                            ->first();
                    } else {
                        $phone_already_exists = User::where('phone', $row['phone'])->first();
                    }
                    if ($phone_already_exists != null) {
                        throw ValidationException::withMessages(['This Phone Already Exists! at row:'.$index+2]);
                    }

                    if (isset($row['id'])) {
                        $email_already_exists = User::where('email', $row['email'])
                            ->where('id', '!=', $row['id'])
                            ->first();
                    } else {
                        $email_already_exists = User::where('email', $row['email'])->first();
                    }
                    
                    if ($email_already_exists != null) {
                        throw ValidationException::withMessages(['This Email Already Exists! at row:'.$index+2]);
                    }
                }
            }
        } catch (ValidationException $e) {
            $failures = $e->validator->getMessageBag()->all();
            return $this->importData['errors'] = [
                'success' => false,
                'message' => $failures[0],
            ];
        }
        
        foreach ($batches as $batch) {
            $insertRows = [];
            $updateRows = [];
            foreach ($batch as $row) {
                if (isset($row['id'])) {
                    $updateRows[] = [
                        'id' => $row['id'],
                        'first_name' => $row['first_name'],
                        'last_name' => $row['last_name'],
                        'email' => $row['email'],
                        'phone' => $row['phone'],
                        'dob' => $row['dob'] ?? '01/04/2002',
                        'gender' => $row['gender'],
                        'updated_at' => now(),
                    ];
                } else {
                    $setting_payload = User::SETTING_PAYLOAD_STRUCTURE;
                    $insertRows[] = [
                        'first_name' => $row['first_name'],
                        'last_name' => $row['last_name'],
                        'email' => $row['email'],
                        'phone' => $row['phone'],
                        'dob' => $row['dob'],
                        'gender' => $row['gender'],
                        'setting_payload' => $setting_payload,
                        'password' => Hash::make('123456'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($updateRows)) {
                User::upsert($updateRows, ['id'], ['first_name', 'last_name', 'email', 'phone', 'dob', 'gender','updated_at']);
            }
            if (!empty($insertRows)) {
                User::insert($insertRows);
                $emails = array_column($insertRows, 'email');
                $agents = User::whereIn('email', $emails)->get();
                foreach ($agents as $agent) {
                    $agent->syncRoles([request()->role]);
                }
            }
        }
    }
}
