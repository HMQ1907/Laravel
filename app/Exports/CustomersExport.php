<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class CustomersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $customers;
    
    public function __construct($customers)
    {
        $this->customers = $customers;
    }


    public function collection()
    {
        $customersCollection = new Collection($this->customers);
    
        return $customersCollection->map(function($customer) {
            return collect($customer)->only(['customer_name', 'email', 'tel_num', 'address']);
        });
    }
    

    public function headings(): array
    {
        return [
            'ten_khach_hang',
            'email',
            'tel_num',
            'address',
        ];
    }
}
