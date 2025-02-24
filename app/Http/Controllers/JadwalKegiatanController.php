<?php

namespace App\Http\Controllers;

use App\Models\JadwalKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class JadwalKegiatanController extends Controller
{
    /**
     * Display a listing of jadwal kegiatan.
     */
    public function index(Request $request)
    {
        try {
            $query = JadwalKegiatan::with('creator');

            // Filter berdasarkan tanggal
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('tanggal_kegiatan', [$request->start_date, $request->end_date]);
            }

            // Filter kegiatan yang akan datang
            if ($request->has('upcoming') && $request->upcoming) {
                $query->where('tanggal_kegiatan', '>=', now());
            }

            // Search berdasarkan judul
            if ($request->has('search')) {
                $query->where('judul_kegiatan', 'like', '%' . $request->search . '%');
            }

            $jadwalKegiatan = $query->orderBy('tanggal_kegiatan', 'asc')->get();

            return response()->json([
                'status' => true,
                'message' => 'Data jadwal kegiatan berhasil diambil',
                'data' => $jadwalKegiatan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data jadwal kegiatan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created jadwal kegiatan.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'judul_kegiatan' => 'required|string|max:255',
                'deskripsi_kegiatan' => 'required|string',
                'tanggal_kegiatan' => 'required|date',
                'file_kegiatan' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120' // max 5MB
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle file upload if exists
            $file_path = null;
            if ($request->hasFile('file_kegiatan')) {
                $file = $request->file('file_kegiatan');
                $file_path = $file->store('jadwal-kegiatan', 'public');
            }

            $jadwalKegiatan = JadwalKegiatan::create([
                'judul_kegiatan' => $request->judul_kegiatan,
                'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
                'tanggal_kegiatan' => $request->tanggal_kegiatan,
                'file_kegiatan' => $file_path,
                'created_by' => Auth::id()
            ]);

            $jadwalKegiatan->load('creator');

            return response()->json([
                'status' => true,
                'message' => 'Jadwal kegiatan berhasil ditambahkan',
                'data' => $jadwalKegiatan
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan jadwal kegiatan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified jadwal kegiatan.
     */
    public function show($id)
    {
        try {
            $jadwalKegiatan = JadwalKegiatan::with('creator')->findOrFail($id);

            return response()->json([
                'status' => true,
                'data' => $jadwalKegiatan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Jadwal kegiatan tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified jadwal kegiatan.
     */
    public function update(Request $request, $id)
    {
        try {
            $jadwalKegiatan = JadwalKegiatan::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'judul_kegiatan' => 'required|string|max:255',
                'deskripsi_kegiatan' => 'required|string',
                'tanggal_kegiatan' => 'required|date',
                'file_kegiatan' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120' // max 5MB
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle file upload if new file exists
            if ($request->hasFile('file_kegiatan')) {
                // Delete old file if exists
                if ($jadwalKegiatan->file_kegiatan) {
                    Storage::disk('public')->delete($jadwalKegiatan->file_kegiatan);
                }
                
                $file = $request->file('file_kegiatan');
                $file_path = $file->store('jadwal-kegiatan', 'public');
                $jadwalKegiatan->file_kegiatan = $file_path;
            }

            $jadwalKegiatan->update([
                'judul_kegiatan' => $request->judul_kegiatan,
                'deskripsi_kegiatan' => $request->deskripsi_kegiatan,
                'tanggal_kegiatan' => $request->tanggal_kegiatan
            ]);

            $jadwalKegiatan->load('creator');

            return response()->json([
                'status' => true,
                'message' => 'Jadwal kegiatan berhasil diupdate',
                'data' => $jadwalKegiatan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengupdate jadwal kegiatan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified jadwal kegiatan.
     */
    public function destroy($id)
    {
        try {
            $jadwalKegiatan = JadwalKegiatan::findOrFail($id);
            
            // Delete file if exists
            if ($jadwalKegiatan->file_kegiatan) {
                Storage::disk('public')->delete($jadwalKegiatan->file_kegiatan);
            }
            
            $jadwalKegiatan->delete();

            return response()->json([
                'status' => true,
                'message' => 'Jadwal kegiatan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus jadwal kegiatan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download file kegiatan.
     */
    public function downloadFile($id)
    {
        try {
            $jadwalKegiatan = JadwalKegiatan::findOrFail($id);

            if (!$jadwalKegiatan->file_kegiatan) {
                return response()->json([
                    'status' => false,
                    'message' => 'File tidak ditemukan'
                ], 404);
            }

            return Storage::disk('public')->download($jadwalKegiatan->file_kegiatan);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengunduh file',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get upcoming events
     */
    public function upcoming()
    {
        try {
            $upcomingEvents = JadwalKegiatan::with('creator')
                ->where('tanggal_kegiatan', '>=', now())
                ->orderBy('tanggal_kegiatan', 'asc')
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Data kegiatan mendatang berhasil diambil',
                'data' => $upcomingEvents
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data kegiatan mendatang',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}