<?php

namespace App\Exports;

use App\Models\SeoTag;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ControlSeoExport implements FromCollection, WithHeadings, WithStyles
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
        return SeoTag::where(
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
                'title' => $row->title ?? 'null',
                'keyword' => $row->keyword ?? 'null',
                'description' => $row->description ?? 'null',
                'remark' => $row->remark ?? 'null',
                ];
            }
        );
    }

    public function headings(): array
    {
        return [
            "Id",
            "Code",
            "Title",
            "Keyword",
            "Description",
            "Remark",
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
