<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportUser implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user_exists = User::where('phone_number', $row['phone_number'])->first();

        if ($user_exists == '') {
            $department = Department::where('name','LIKE', '%'.$row['department'].'%')->first();
            $user = User::create([
                "name" => $row['name'],
                "job_title" => $row['job_title'],
                "email" => $row['email'],
                "department_id" => $department != '' ? $department->id : '',
                "site" => $row['site'],
                "phone_number" => $row['phone_number'],
                "country" => $row['country'],
                "personal_email" => $row['personal_email'],
                "password" => bcrypt('password'),
            ]);

        }
    }
}
