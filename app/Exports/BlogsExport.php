<?php

namespace App\Exports;

use App\Models\Blog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BlogsExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $ids;
// return $request->all();
    function __construct($ids)
    {
            $this->ids = $ids;
    }

    public function collection()
    {
        $ids = $this->ids;
        return Blog::where(
            function ($query) use ($ids) {
                if (!empty($ids) && is_array($ids)) {
                    $query->whereIn('id', $ids);
                }
            }
        )->get()->map(
            function ($row) {
                return [
                'id' => $row->id ?? 'null',
                'title' => $row->title ?? 'null',
                'slug' => $row->slug ?? 'null',
                'type' => $row->type ?? 'null', // duplicated key removed
                'category_id' => $row->category_id ?? 'null',
                'type' => $row->type ?? 'null',
                'description' => strip_tags($row->description) ?? 'null',
                'short_description' => strip_tags($row->short_description) ?? 'null',
                ];
            }
        );
    }

    public function headings(): array
    {
        return [
            "Id",
            "Title",
            "Slug",
            "Type", 
            "Category Id",
            "Description Banner",
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
