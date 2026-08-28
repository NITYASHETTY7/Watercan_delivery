<?php namespace App\Exports; 
use App\Models\PromoCode; 
use Maatwebsite\Excel\Concerns\FromCollection; 
use Maatwebsite\Excel\Concerns\WithHeadings; 
use Maatwebsite\Excel\Concerns\WithStyles; 
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; 
class PromoCodeExport implements FromCollection, WithHeadings, WithStyles 
{ 
    /** * @return  \Illuminate\Support\Collection */ 
    protected $ids; 
    function __construct($ids) { $this->ids = $ids;
    }

    public function collection()
    {
    $ids = $this->ids;
    return PromoCode::where(function ($query) use ($ids){
    if (!empty($ids))
    $query->whereIn('id', $ids);

    })->get()->map(function($row) {
        return [
                'id' => $row->id ?? null,  
                'type' => $row->type ?? null,                                                                      
                'code' => $row->code ?? null,                                                                      
                'max_uses' => $row->max_uses ?? null,                                                              
                'value' => $row->value ?? null,                                                                      
                'expire_at' => $row->expire_at ?? null,                                                                      
            ];
        });
    }

    public function headings(): array
    {
        return [
            "Id",                                                                        
            "Type",                                                                        
            "Code",                                                                        
            "Max Uses",                                                                        
            "Value",                                                                        
            "Expire At",                                                                        
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
