<?php

namespace App\Http\Controllers;

use App\Models\MasterPegawai;
use App\Services\PegawaiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PegawaiController extends Controller
{
    protected $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
        
        // Hanya superadmin yang bisa CRUD pegawai
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isSuperAdmin()) {
                abort(403, 'Tidak memiliki akses untuk mengelola data pegawai');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MasterPegawai::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereHas('user', function ($q) {
                    $q->where('is_active', true);
                });
            } elseif ($request->status === 'inactive') {
                $query->whereHas('user', function ($q) {
                    $q->where('is_active', false);
                });
            }
        }

        $pegawais = $query->latest()->paginate(20);

        return view('pegawai.index', compact('pegawais'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pegawai.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'gelar' => 'nullable|string|max:255',
            'alias' => 'nullable|string|max:255',
            'nip_lama' => 'required|string|max:255',
            'nip_baru' => 'nullable|string|max:255',
            'nik' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:master_pegawais,email|unique:users,email',
            'nomor_hp' => 'nullable|string|max:255',
            'pangkat' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'pendidikan_tertinggi' => 'nullable|string|max:255',
            'program_studi' => 'nullable|string|max:255',
            'universitas' => 'nullable|string|max:255',
        ]);

        try {
            $pegawai = $this->pegawaiService->createPegawai($validated);
            
            return redirect()->route('pegawai.index')
                           ->with('success', 'Pegawai berhasil ditambahkan dan akun user telah dibuat');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterPegawai $pegawai)
    {
        $pegawai->load('user.roles');
        return view('pegawai.show', compact('pegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterPegawai $pegawai)
    {
        return view('pegawai.edit', compact('pegawai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterPegawai $pegawai)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'gelar' => 'nullable|string|max:255',
            'alias' => 'nullable|string|max:255',
            'nip_lama' => 'required|string|max:255',
            'nip_baru' => 'nullable|string|max:255',
            'nik' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:master_pegawais,email,'.$pegawai->id.'|unique:users,email,'.$pegawai->user?->id,
            'nomor_hp' => 'nullable|string|max:255',
            'pangkat' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'pendidikan_tertinggi' => 'nullable|string|max:255',
            'program_studi' => 'nullable|string|max:255',
            'universitas' => 'nullable|string|max:255',
        ]);

        try {
            $this->pegawaiService->updatePegawai($pegawai, $validated);
            
            return redirect()->route('pegawai.index')
                           ->with('success', 'Data pegawai berhasil diperbarui');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterPegawai $pegawai)
    {
        try {
            $pegawai->delete(); // Akan melakukan soft delete dan deactivate user
            
            return redirect()->route('pegawai.index')
                           ->with('success', 'Pegawai berhasil dihapus dan akun user telah dinonaktifkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pegawai: ' . $e->getMessage());
        }
    }

    /**
     * Restore soft deleted pegawai
     */
    public function restore($id)
    {
        $pegawai = MasterPegawai::withTrashed()->findOrFail($id);
        
        try {
            $pegawai->restore(); // Akan mengaktifkan kembali user
            
            return redirect()->route('pegawai.index')
                           ->with('success', 'Pegawai berhasil direstore dan akun user telah diaktifkan kembali');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal restore pegawai: ' . $e->getMessage());
        }
    }

    /**
     * Show import form
     */
    public function importForm()
    {
        return view('pegawai.import');
    }

    /**
     * Process Excel import
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls|max:10240', // Max 10MB
        ]);

        try {
            // Load Excel file
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();

            // Convert to associative array (assuming first row is header)
            $headers = array_shift($data);
            $pegawaiData = [];
            
            foreach ($data as $row) {
                if (empty(array_filter($row))) continue; // Skip empty rows
                
                $pegawaiData[] = array_combine($headers, $row);
            }

            // Validate minimum data
            if (empty($pegawaiData)) {
                return back()->with('error', 'File Excel tidak berisi data yang valid');
            }

            // Process import
            $results = $this->pegawaiService->importPegawai($pegawaiData);

            // Prepare session data for results page
            session([
                'import_results' => $results,
                'import_timestamp' => now(),
            ]);

            return redirect()->route('pegawai.import.results');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }
    }

    /**
     * Show import results
     */
    public function importResults()
    {
        $results = session('import_results');
        
        if (!$results) {
            return redirect()->route('pegawai.index')
                           ->with('error', 'Data hasil import tidak ditemukan');
        }

        return view('pegawai.import-results', compact('results'));
    }

    /**
     * Download error report from import
     */
    public function downloadErrorReport()
    {
        $results = session('import_results');
        
        if (!$results || empty($results['errors'])) {
            return redirect()->route('pegawai.index')
                           ->with('error', 'Tidak ada data error untuk didownload');
        }

        try {
            // Generate error report
            $errorData = $this->pegawaiService->generateErrorReport($results['errors']);
            
            // Create Excel file
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set headers
            $headers = ['Baris', 'Email', 'Status', 'Pesan Error'];
            $sheet->fromArray($headers, null, 'A1');
            
            // Add data
            $sheet->fromArray($errorData, null, 'A2');
            
            // Style headers
            $sheet->getStyle('A1:D1')->getFont()->setBold(true);
            
            // Auto-size columns
            foreach (range('A', 'D') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Create writer and download
            $writer = new Xlsx($spreadsheet);
            $filename = 'error_report_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            // Output to browser
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate error report: ' . $e->getMessage());
        }
    }

    /**
     * Download template Excel
     */
    public function downloadTemplate()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set headers sesuai dengan struktur database
            $headers = [
                'Nama Lengkap',
                'Jenis Kelamin',
                'Gelar',
                'Alias',
                'NIP Lama',
                'NIP Baru',
                'NIK',
                'Email',
                'Nomor HP',
                'Pangkat',
                'Jabatan',
                'Pendidikan Tertinggi',
                'Program Studi',
                'Universitas'
            ];
            
            $sheet->fromArray($headers, null, 'A1');
            
            // Add sample data
            $sampleData = [
                [
                    'John Doe',
                    'L',
                    ', S.Kom.',
                    'John',
                    '123456789',
                    '199001012020121001',
                    '1234567890123456',
                    'john@bps.go.id',
                    '081234567890',
                    '3A',
                    'Programmer',
                    'S1',
                    'Teknik Informatika',
                    'Universitas Contoh'
                ]
            ];
            
            $sheet->fromArray($sampleData, null, 'A2');
            
            // Style headers
            $sheet->getStyle('A1:N1')->getFont()->setBold(true);
            $sheet->getStyle('A1:N1')->getFill()
                  ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                  ->getStartColor()->setRGB('E3F2FD');
            
            // Auto-size columns
            foreach (range('A', 'N') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Add data validation for Jenis Kelamin
            $validation = $sheet->getCell('B2')->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setErrorTitle('Input Error');
            $validation->setError('Pilih L atau P');
            $validation->setFormula1('"L,P"');
            
            // Create writer and download
            $writer = new Xlsx($spreadsheet);
            $filename = 'template_import_pegawai.xlsx';
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate template: ' . $e->getMessage());
        }
    }
}