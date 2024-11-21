<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::all();
        $roomTypes = RoomType::all();
        return view('hotels.index', compact('hotels', 'roomTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:hotels|max:255',
            'tax_id_number' => 'required',
            'address' => 'required',
            'city' => 'required',
            'max_rooms' => 'required|integer',
        ], [
            'name.required' => 'El nombre del hotel es obligatorio.',
            'name.unique' => 'El nombre del hotel ya está registrado.',
            'name.max' => 'El nombre del hotel no puede superar los 255 caracteres.',
            'tax_id_number.required' => 'El ID tributario es obligatorio.',
            'address.required' => 'La dirección es obligatoria.',
            'city.required' => 'La ciudad es obligatoria.',
            'max_rooms.required' => 'El número máximo de habitaciones es obligatorio.',
            'max_rooms.integer' => 'El número máximo de habitaciones debe ser un número entero.',
        ]);

        DB::beginTransaction();
        try {
            $hotel = Hotel::create($request->all());

            foreach ($request->room_type_id as $key => $val) {
                $chargeExaEnfa = Room::updateOrCreate(
                    [
                        'hotel_id' => $hotel->id,
                        'room_type_id' => $val,
                        'accommodation_id' => $request->accommodation_id[$key],
                        'quantity' => $request->quantity[$key],
                    ],
                    [
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            }

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
        $hotel = Hotel::findOrFail($id);
        return response()->json($hotel);
    }

    public function edit($id)
    {
        $hotel = Hotel::with('rooms', 'rooms.roomType', 'rooms.accommodation')->findOrFail($id);
        return response()->json($hotel);
    }

    public function update(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);
        $request->validate([
            'name' => 'required|max:255|unique:hotels,name,' . $hotel->id,
            'tax_id_number' => 'required',
            'address' => 'required',
            'city' => 'required',
            'max_rooms' => 'required|integer',
        ], [
            'name.required' => 'El nombre del hotel es obligatorio.',
            'name.unique' => 'El nombre del hotel ya está registrado.',
            'name.max' => 'El nombre del hotel no puede superar los 255 caracteres.',
            'tax_id_number.required' => 'El ID tributario es obligatorio.',
            'address.required' => 'La dirección es obligatoria.',
            'city.required' => 'La ciudad es obligatoria.',
            'max_rooms.required' => 'El número máximo de habitaciones es obligatorio.',
            'max_rooms.integer' => 'El número máximo de habitaciones debe ser un número entero.',
        ]);

        DB::beginTransaction();
        try {
            $hotel->update($request->all());

            // Obtén los IDs de los registros enviados en el request
            $newData = $request->only(['room_type_id', 'accommodation_id', 'quantity']);

            // Combinar los datos en un formato para la comparación
            $incomingRecords = collect($request->room_type_id)->map(function ($typeId, $key) use ($request, $hotel) {
                return [
                    'hotel_id' => $hotel->id,
                    'room_type_id' => $typeId,
                    'accommodation_id' => $request->accommodation_id[$key],
                    'quantity' => $request->quantity[$key],
                ];
            });

            // Obtén los registros actuales del hotel desde la base de datos
            $existingRecords = Room::where('hotel_id', $hotel->id)->get(['id', 'room_type_id', 'accommodation_id']);

            // Determina los registros que deben ser eliminados
            $recordsToDelete = $existingRecords->filter(function ($record) use ($incomingRecords) {
                return !$incomingRecords->contains(function ($incoming) use ($record) {
                    return $incoming['room_type_id'] === $record->room_type_id
                    && $incoming['accommodation_id'] === $record->accommodation_id;
                });
            });

            // Elimina los registros que ya no están presentes
            Room::destroy($recordsToDelete->pluck('id'));

            // Procesa los datos enviados en el request
            foreach ($incomingRecords as $record) {
                Room::updateOrCreate(
                    [
                        'hotel_id' => $record['hotel_id'],
                        'room_type_id' => $record['room_type_id'],
                        'accommodation_id' => $record['accommodation_id'],
                    ],
                    [
                        'quantity' => $record['quantity'],
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]
                );
            }

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
            $hotel = Hotel::findOrFail($id);
            if ($hotel->delete()) {
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
