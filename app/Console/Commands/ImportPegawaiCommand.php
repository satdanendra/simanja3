<?php

namespace App\Console\Commands;

use App\Services\PegawaiService;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportPegawaiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pegawai:import {file : Path to Excel file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import pegawai dari file Excel via command line';

    protected $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        parent::__construct();
        $this->pegawaiService = $pegawaiService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');

        // Validasi file exists
        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return 1;
        }

        $this->info("Memulai import dari file: {$filePath}");

        try {
            // Load Excel file
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();

            // Convert to associative array
            $headers = array_shift($data);
            $pegawaiData = [];
            
            foreach ($data as $row) {
                if (empty(array_filter($row))) continue; // Skip empty rows
                $pegawaiData[] = array_combine($headers, $row);
            }

            if (empty($pegawaiData)) {
                $this->error('File Excel tidak berisi data yang valid');
                return 1;
            }

            $this->info("Ditemukan " . count($pegawaiData) . " data pegawai");

            // Create progress bar
            $progressBar = $this->output->createProgressBar(count($pegawaiData));
            $progressBar->start();

            // Process import
            $results = $this->pegawaiService->importPegawai($pegawaiData);

            $progressBar->finish();
            $this->newLine(2);

            // Display results
            $this->displayResults($results);

            // Save error report if there are errors
            if (!empty($results['errors'])) {
                $this->saveErrorReport($results['errors']);
            }

            return 0;

        } catch (\Exception $e) {
            $this->error("Gagal memproses file Excel: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Display import results
     */
    private function displayResults(array $results): void
    {
        $this->newLine();
        $this->info("=== HASIL IMPORT ===");
        $this->info("Berhasil dibuat: " . $results['success']);
        $this->info("Berhasil diupdate: " . $results['updated']);
        $this->info("Gagal: " . $results['failed']);
        $this->info("Total: " . ($results['success'] + $results['updated'] + $results['failed']));

        if (!empty($results['errors'])) {
            $this->newLine();
            $this->warn("DAFTAR ERROR:");
            
            foreach ($results['errors'] as $error) {
                $this->line("Baris {$error['row']} ({$error['email']}): {$error['message']}");
            }
        }
    }

    /**
     * Save error report to file
     */
    private function saveErrorReport(array $errors): void
    {
        $filename = 'error_report_' . date('Y-m-d_H-i-s') . '.csv';
        $filePath = storage_path('app/' . $filename);

        $file = fopen($filePath, 'w');
        
        // Write headers
        fputcsv($file, ['Baris', 'Email', 'Status', 'Pesan Error']);
        
        // Write data
        foreach ($errors as $error) {
            fputcsv($file, [
                $error['row'],
                $error['email'],
                $error['status'],
                $error['message']
            ]);
        }
        
        fclose($file);

        $this->newLine();
        $this->info("Error report disimpan di: {$filePath}");
    }
}