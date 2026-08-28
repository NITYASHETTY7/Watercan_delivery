<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeadsExport implements FromCollection, WithHeadings, WithStyles
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
        return Lead::where(
            function ($query) use ($ids) {
                if (is_array($ids) && !empty($ids)) {
                    $query->whereIn('id', $ids);
                }
            }
        )->get()->map(
            function ($row) {
                return [
                    'id' => $row->id ?? null,
                    'user_id' => $row->user_id ?? null,
                    'name' => $row->name ?? null,
                    'lead_type_id' => $row->lead_type_id ?? null,
                    'lead_source_id' => $row->lead_source_id ?? null,
                    'owner_email' => $row->owner_email ?? null,
                    'remark' => $row->remark ?? null,
                    'address' => $row->address ?? null,
                    'phone' => $row->phone ?? null,
                    'status' => $row->status ?? null,
                ];
            }
        );
    }

    public function headings(): array
    {
        return [
            "Id",
            "User Id",
            "Name",
            "Lead Type Id",
            "Lead Source Id",
            "Email",
            "Remark",
            "Address",
            "Phone",
            "Status",
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
            ],
        ];
    }
}
