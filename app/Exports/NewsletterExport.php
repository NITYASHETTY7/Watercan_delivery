<?php

namespace App\Exports;

use App\Models\NewsLetter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NewsletterExport implements FromCollection, WithHeadings, WithStyles
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
        return NewsLetter::where(
            function ($query) use ($ids) {
                if (!empty($ids) && is_array($ids)) {
                    $query->whereIn('id', $ids);
                }
            }
        )->get()->map(
            function ($row) {
                return [
                'id' => $row->id ?? null,
                'type' => $row->type ?? null,
                'value' => $row->value ?? null,
                'name' => $row->name ?? null,
                ];
            }
        );
    }

    public function headings(): array
    {
        return [
            "Id",
            "Type",
            "Value",
            "Name",
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
