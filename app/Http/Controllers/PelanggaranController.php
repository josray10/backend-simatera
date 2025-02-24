<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PelanggaranController extends Controller
{
    /**
     * Display a listing of pelanggaran.
     */
    public function index(Request $request)
    {
        try {
            $query = Pelanggaran::with(['mahasiswa', 'creator']);

            // Filter berdasarkan NIM
            if ($request->has('nim')) {
                $query->where('nim', $request->nim);
            }

            // Filter berdasarkan tanggal
            if ($request->has('tanggal')) {
                $query->whereDate('tanggal_pelanggaran', $request->tanggal);
            }

            // Filter berdasarkan date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('tanggal_pelanggaran', [$request->start_date, $request->end_date]);
            }

            $pelanggaran = $query->latest()->get();

            return response()->json([
                'status' => true,
                'message' => 'Data pelanggaran berhasil diambil',
                'data' => $pelanggaran
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data pelanggaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created pelanggaran.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nim' => 'required|exists:mahasiswa,nim',
                'tanggal_pelanggaran' => 'required|date',
                'keterangan_pelanggaran' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verify mahasiswa exists
            $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();
            if (!$mahasiswa) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mahasiswa tidak ditemukan'
                ], 404);
            }

            $pelanggaran = Pelanggaran::create([
                'nim' => $request->nim,
                'tanggal_pelanggaran' => $request->tanggal_pelanggaran,
                'keterangan_pelanggaran' => $request->keterangan_pelanggaran,
                'created_by' => Auth::id()
            ]);

            $pelanggaran->load(['mahasiswa', 'creator']);

            return response()->json([
                'status' => true,
                'message' => 'Data pelanggaran berhasil ditambahkan',
                'data' => $pelanggaran
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan data pelanggaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified pelanggaran.
     */
    public function show($id)
    {
        try {
            $pelanggaran = Pelanggaran::with(['mahasiswa', 'creator'])->findOrFail($id);

            return response()->json([
                'status' => true,
                'data' => $pelanggaran
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data pelanggaran tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified pelanggaran.
     */
    public function update(Request $request, $id)
    {
        try {
            $pelanggaran = Pelanggaran::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'tanggal_pelanggaran' => 'required|date',
                'keterangan_pelanggaran' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $pelanggaran->update($request->only([
                'tanggal_pelanggaran',
                'keterangan_pelanggaran'
            ]));

            $pelanggaran->load(['mahasiswa', 'creator']);

            return response()->json([
                'status' => true,
                'message' => 'Data pelanggaran berhasil diupdate',
                'data' => $pelanggaran
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengupdate data pelanggaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified pelanggaran.
     */
    public function destroy($id)
    {
        try {
            $pelanggaran = Pelanggaran::findOrFail($id);
            $pelanggaran->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data pelanggaran berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data pelanggaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pelanggaran by mahasiswa NIM
     */
    public function getByNim($nim)
    {
        try {
            $pelanggaran = Pelanggaran::with(['mahasiswa', 'creator'])
                ->where('nim', $nim)
                ->latest()
                ->get();

            return response()->json([
                'status' => true,
                'data' => $pelanggaran
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data pelanggaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}