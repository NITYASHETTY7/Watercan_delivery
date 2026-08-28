<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $ids;

    function __construct($ids)
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        $ids = $this->ids;
        return User::where(
            function ($query) use ($ids) {
                if (count($ids) > 0) {
                    $query->whereIn('id', $ids);
                }
            }
        )->get()->map(
            function ($row) {
                return [
                'id' => $row->id !== null ? $row->id : 'null',
                'first_name' => $row->first_name !== null ? $row->first_name : 'null',
                'last_name' => $row->last_name !== null ? $row->last_name : 'null',
                'email' => $row->email !== null ? $row->email : 'null',
                'phone' => $row->phone !== null ? $row->phone : 'null',
                'dob' => $row->dob !== null ? $row->dob : 'null',
                'gender' => $row->gender !== null ? $row->gender : 'null',
                ];
            }
        );
    }

    public function headings(): array
    {
        return [
            "Id",
            "First Name",
            "Last Name",
            "Email",
            "Phone",
            "Dob",
            "Gender",
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => [
                'font' => ['bold' => true],
            ],
        ];
    }
}
