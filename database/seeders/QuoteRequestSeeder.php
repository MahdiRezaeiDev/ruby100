<?php

namespace Database\Seeders;

use App\Models\QuoteRequest;
use Illuminate\Database\Seeder;

class QuoteRequestSeeder extends Seeder
{
    public function run(): void
    {
        if (QuoteRequest::query()->exists()) {
            return;
        }

        $rows = [
            ['John Smith', '0401123456', 'john@example.com', 'Towing Services', 'quote', 0],
            ['Sarah Lee', '0412987654', 'sarah@example.com', 'Cash for Cars', 'cash_for_cars', 1],
            ['Mike Brown', '0433555123', 'mike@example.com', 'Car Removal', 'quote', 2],
            ['Amy Nguyen', '0455666777', 'amy@example.com', 'Emergency Assistance', 'quote', 3],
        ];

        foreach ($rows as $row) {
            QuoteRequest::query()->create([
                'name' => $row[0],
                'phone' => $row[1],
                'email' => $row[2],
                'service' => $row[3],
                'type' => $row[4],
                'status' => 'new',
                'message' => 'Sample request for dashboard',
                'ip_address' => '127.0.0.1',
                'created_at' => now()->subDays($row[5]),
                'updated_at' => now()->subDays($row[5]),
            ]);
        }
    }
}
