<!-- Modal -->
<div class="modal fade" id="accommodationModal" tabindex="-1" role="dialog" aria-labelledby="accommodationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accommodationModalLabel">Agregar Acomodación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="accommodationForm">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="accommodation">Acomodación</label>
                        <input type="text" class="form-control" name="accommodation" id="accommodation" required>
                    </div>
                    <div class="form-group">
                        <label for="room_type_id">Tipo de Habitación</label>
                        <select class="form-control" name="room_type_id" id="room_type_id" required>
                            @foreach ($roomTypes as $roomType)
                            <option value="{{ $roomType->id }}">
                                {{ $roomType->type }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>