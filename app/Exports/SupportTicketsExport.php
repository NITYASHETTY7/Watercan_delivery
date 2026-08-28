<?php

namespace App\Exports;

use App\Models\SupportTicket;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SupportTicketsExport implements FromCollection, WithHeadings, WithStyles
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
        return SupportTicket::where(
            function ($query) use ($ids) {
                if (!empty($ids) && is_array($ids)) {
                    $query->whereIn('id', $ids);
                }
            }
        )->get()->map(
            function ($row) {
                return [
                'id' => $row->id !== null ? $row->id : 'null',
                'user_id' => $row->user_id !== null ? $row->user_id : 'null',
                'subject' => $row->subject !== null ? $row->subject : 'null',
                'message' => $row->message !== null ? $row->message : 'null',
                'priority' => $row->priority !== null ? $row->priority : 'null',
                // 'category' => $row->category ?? 'null',
                'ticket_type_id' => $row->ticket_type_id !== null ? $row->ticket_type_id : 'null',
                ];
            }
        );
    }

    public function headings(): array
    {
        return [
            "Id",
            "User Id",
            "Subject",
            "Message",
            "Priority",
            // "Category",
            "Ticket Type Id",
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
