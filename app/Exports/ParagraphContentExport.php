<?php

namespace App\Exports;

use App\Models\ParagraphContent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParagraphContentExport implements FromCollection, WithHeadings, WithStyles
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
        return ParagraphContent::where(
            function ($query) use ($ids) {
                if (!empty($ids) && is_array($ids)) {
                    $query->whereIn('id', $ids);
                }
            }
        )->get()->map(
            function ($row) {
                return [
                'id' => $row->id ?? 'null',
                'code' => $row->code ?? 'null',
                'type' => $row->type ?? 'null',
                'group' => $row->group ?? 'null',
                'value' => $row->value ?? 'null',
                ];
            }
        );
    }

    public function headings(): array
    {
        return [
            "Id",
            "Code",
            "Type",
            "Group",
            "Value",
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
