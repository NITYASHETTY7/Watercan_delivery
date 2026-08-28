<?php

namespace App\Exports;

use App\Models\WebsiteEnquiry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WebsiteEnquiresExport implements FromCollection, WithHeadings, WithStyles
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
        return WebsiteEnquiry::where(
            function ($query) use ($ids) {
                if (!empty($ids) && is_array($ids)) {
                    $query->whereIn('id', $ids);
                }
            }
        )->get()->map(
            function ($row) {
                return [
                'id' => $row->id !== null ? $row->id : 'null',
                'name' => $row->name ?? null,
                'email' => $row->email ?? null,
                'phone' => $row->phone ?? null,
                'subject' => $row->subject ?? null,
                'description' => $row->description ?? null,
                ];
            }
        );
    }

    public function headings(): array
    {
        return [
            "Id",
            "Name",
            "Email",
            "Phone",
            "Subject",
            "Description",
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'color' => ['red' => true],
                'text' => ['yellow' => true],
            ],
        ];
    }
}
