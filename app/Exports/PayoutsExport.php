<?php

namespace App\Exports;

use App\Models\Payout;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PayoutsExport implements FromCollection, WithHeadings, WithStyles
{
    protected $ids;

    function __construct($ids)
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        $ids = $this->ids;
        return Payout::whereIn('id', $ids)->get()->map(function ($row) {
            return [
                $row->id !== null ? $row->id : 'null',
                $row->user ? $row->user->name : 'N/A',  // Handle null user
                $row->amount !== null ? $row->amount : 'null',
                $row->status !== null ? $row->status : 'null',
                $row->created_at !== null ? $row->created_at->format('Y-m-d') : 'null',
                $row->updated_at !== null ? $row->updated_at->format('Y-m-d') : 'null',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Id',
            'User Name',
            'Amount',
            'Status',
            'Created At',
            'Updated At',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
