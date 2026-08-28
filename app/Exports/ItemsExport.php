<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemsExport implements FromCollection, WithHeadings, WithStyles
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
        return Item::where(
            function ($query) use ($ids) {
                if (!empty($ids) && is_array($ids)) {
                    $query->whereIn('id', $ids);
                }
            }
        )->get()->map(
            function ($row) {
                return [
                'id' => $row->id ?? null,
                'name' => $row->name ?? null,
                'sell_price' => $row->sell_price ?? null,
                'mrp_price' => $row->mrp_price ?? null,
                'buy_price' => $row->buy_price ?? null,
                'sku' => $row->sku ?? null,
                'tax_percent' => $row->tax_percent ?? null,
                'category_id' => $row->category_id ?? null,
                'description' => strip_tags($row->description) ?? null,
                'short_description' => strip_tags($row->short_description) ?? null,
                ];
            }
        );
    }

    public function headings(): array
    {
        return [
            "Id",
            "Name",
            "Sell Price",
            "Mrp Price",
            "Buy Price",
            "Sku",
            "Tax Percent",
            "Category Id",
            "Description",
            "Short Description",
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

