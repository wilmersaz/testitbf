<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AccommodationController extends Controller
{
    public function index()
    {
        $accommodations = Accommodation::with('roomType')->get();
        $roomTypes = RoomType::all();
        return view('accommodations.index', compact('accommodations', 'roomTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'accommodation' => [
                'required',
                'max:255',
                Rule::unique('accommodations')->where(function ($query) use ($request) {
                    return $query->where('room_type_id', $request->room_type_id);
                }),
            ],
        ], [
            'room_type_id.required' => 'El campo tipo de habitación es obligatorio.',
            'room_type_id.exists' => 'El tipo de habitación seleccionado no existe.',
            'accommodation.required' => 'El campo acomodación es obligatorio.',
            'accommodation.max' => 'La acomodación no puede exceder los 255 caracteres.',
            'accommodation.unique' => 'Ya existe una acomodación con este tipo de habitación.',
        ]);

        DB::beginTransaction();
        try {
            $accommodation = Accommodation::create($request->all());
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

    // buscar acomodaciones segun el tipo de habitacion seleccionada (id)
    public function show($id)
    {
        $accommodation = Accommodation::where('room_type_id',$id)->get();

        return response()->json($accommodation);
    }

    public function edit($id)
    {
        $accommodation = Accommodation::findOrFail($id);
        return response()->json($accommodation);
    }

    public function update(Request $request, $id)
    {
        $accommodation = Accommodation::findOrFail($id);
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'accommodation' => [
                'required',
                'max:255',
                Rule::unique('accommodations')->where(function ($query) use ($request) {
                    return $query->where('room_type_id', $request->room_type_id);
                })->ignore($accommodation->id),
            ],
        ], [
            'room_type_id.required' => 'El campo tipo de habitación es obligatorio.',
            'room_type_id.exists' => 'El tipo de habitación seleccionado no existe.',
            'accommodation.required' => 'El campo acomodación es obligatorio.',
            'accommodation.max' => 'La acomodación no puede exceder los 255 caracteres.',
            'accommodation.unique' => 'Ya existe una acomodación con este tipo de habitación.',
        ]);

        DB::beginTransaction();
        try {
            $accommodation->update($request->all());
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
            $accommodation = Accommodation::findOrFail($id);
            if ($accommodation->delete()) {
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
