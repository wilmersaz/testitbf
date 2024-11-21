<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::all();
        return view('room-types.index', compact('roomTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|unique:room_types|max:255',
        ], [
            'type.required' => 'El campo tipo de habitación es obligatorio.',
            'type.unique' => 'Ya existe un tipo de habitación con este nombre.',
            'type.max' => 'El tipo de habitación no puede exceder los 255 caracteres.',
        ]);

        DB::beginTransaction();
        try {
        $roomType = RoomType::create($request->all());
        DB::commit();
            return response()->json([
                'title' => 'Registro creado',
                'message' => 'Registro creado exitosamente',
                'icon' => 'success',
            ], 201);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'title' => 'Error',
                'message' => $th->getMessage(),
                'icon' => 'error',
            ], 500);
        }
    }

    public function show($id)
    {
        $roomType = RoomType::findOrFail($id);
        return response()->json($roomType);
    }

    public function edit($id)
    {
        $roomType = RoomType::findOrFail($id);
        return response()->json($roomType);
    }

    public function update(Request $request, $id)
    {
        $roomType = RoomType::findOrFail($id);
        $request->validate([
            'type' => 'required|max:255|unique:room_types,type,' . $roomType->id,
        ], [
            'type.required' => 'El campo tipo de habitación es obligatorio.',
            'type.unique' => 'Ya existe un tipo de habitación con este nombre.',
            'type.max' => 'El tipo de habitación no puede exceder los 255 caracteres.',
        ]);

        DB::beginTransaction();
        try {
        $roomType->update($request->all());
        DB::commit();
            return response()->json([
                'title' => 'Registro actualizado',
                'message' => 'Registro actualizado exitosamente',
                'icon' => 'success',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'title' => 'Error',
                'message' => $th->getMessage(),
                'icon' => 'error',
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $roomType = RoomType::findOrFail($id);
            if ($roomType->delete()) {
                DB::commit();
                return response()->json([
                    'title' => 'Registro eliminado',
                    'message' => 'Registro eliminado exitosamente',
                    'icon' => 'success',
                ], 200);
            } else {
                return response()->json([
                    'title' => 'Registro no eliminado',
                    'message' => 'Hubo un error desconocido. Por favor, inténtalo nuevamente',
                    'icon' => 'warning',
                ], 404);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'title' => 'Error',
                'message' => $th->getMessage(),
                'icon' => 'error',
            ], 500);
        }
    }
}
