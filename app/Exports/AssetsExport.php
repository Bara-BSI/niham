<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $assets;

    protected $rowIndex = 0; // Property to keep track of the row number

    // Accept the collection via the constructor
    public function __construct(Collection $assets)
    {
        $this->assets = $assets;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Simply return the collection we received
        return $this->assets;
    }

    public function headings(): array
    {
        // Add the 'No.' column for our sequential number
        return [
            'No.',
            'Tag',
            'Asset Name',
            'Category',
            'Department',
            'Status',
            'Serial Number',
            'Purchase Date',
            'Warranty Date',
            'Purchase Cost',
            'Vendor',
            'Remarks',
        ];
    }

    /**
     * @param  mixed  $asset
     */
    public function map($asset): array
    {
        // Map the data and add the incrementing row number
        return [
            ++$this->rowIndex, // This is our sequential number
            $asset->tag,
            $asset->name,
            $asset->category->name,
            $asset->department->name,
            $asset->status,
            $asset->serial_number,
            $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : 'N/A',
            $asset->warranty_date ? $asset->warranty_date->format('Y-m-d') : 'N/A',
            $asset->purchase_cost,
            $asset->vendor,
            $asset->remarks,
        ];
    }
}
