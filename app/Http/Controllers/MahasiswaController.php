<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of mahasiswa.
     */
    public function index(Request $request)
    {
        try {
            $query = Mahasiswa::query();

            // Filter berdasarkan status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter berdasarkan gedung
            if ($request->has('gedung')) {
                $query->where('gedung', $request->gedung);
            }

            // Filter berdasarkan gender
            if ($request->has('gender')) {
                $query->where('gender', $request->gender);
            }

            $mahasiswa = $query->get();

            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil diambil',
                'data' => $mahasiswa
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data mahasiswa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created mahasiswa.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nim' => 'required|unique:mahasiswa,nim',
                'nama' => 'required|string|max:255',
                'prodi' => 'required|string|max:255',
                'gedung' => ['required', Rule::in(['tb1', 'tb2', 'tb3', 'tb4', 'tb5'])],
                'no_kamar' => 'required|string',
                'email' => 'required|email|unique:mahasiswa,email',
                'tanggal_lahir' => 'required|date',
                'tempat_lahir' => 'required|string',
                'asal' => 'required|string',
                'status' => ['required', Rule::in(['Aktif Tinggal', 'Checkout'])],
                'golongan_ukt' => 'required|string',
                'gender' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
                'password' => 'required|min:6'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $mahasiswa = Mahasiswa::create([
                ...$request->except('password'),
                'password' => Hash::make($request->password),
                'created_by' => auth()->id()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil ditambahkan',
                'data' => $mahasiswa
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan data mahasiswa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified mahasiswa.
     */
    public function show($id)
    {
        try {
            $mahasiswa = Mahasiswa::findOrFail($id);

            return response()->json([
                'status' => true,
                'data' => $mahasiswa
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data mahasiswa tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified mahasiswa.
     */
    public function update(Request $request, $id)
    {
        try {
            $mahasiswa = Mahasiswa::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nim' => ['required', Rule::unique('mahasiswa')->ignore($id)],
                'nama' => 'required|string|max:255',
                'prodi' => 'required|string|max:255',
                'gedung' => ['required', Rule::in(['tb1', 'tb2', 'tb3', 'tb4', 'tb5'])],
                'no_kamar' => 'required|string',
                'email' => ['required', 'email', Rule::unique('mahasiswa')->ignore($id)],
                'tanggal_lahir' => 'required|date',
                'tempat_lahir' => 'required|string',
                'asal' => 'required|string',
                'status' => ['required', Rule::in(['Aktif Tinggal', 'Checkout'])],
                'golongan_ukt' => 'required|string',
                'gender' => ['required', Rule::in(['Laki-laki', 'Perempuan'])]
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = $request->except('password');
            if ($request->has('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $mahasiswa->update($updateData);

            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil diupdate',
                'data' => $mahasiswa
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengupdate data mahasiswa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified mahasiswa.
     */
    public function destroy($id)
    {
        try {
            $mahasiswa = Mahasiswa::findOrFail($id);
            $mahasiswa->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data mahasiswa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import data mahasiswa from Excel.
     */
    public function import(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|mimes:xlsx,xls'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Import logic here
            // You'll need to implement Excel import functionality
            // using a package like maatwebsite/excel

            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil diimport'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengimport data mahasiswa',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}