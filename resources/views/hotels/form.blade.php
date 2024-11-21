<div class="modal fade" id="hotelModal" tabindex="-1" role="dialog" aria-labelledby="hotelModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hotelModalLabel">Agregar Hotel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="hotelForm">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" name="name" id="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tax_id_number">Nit</label>
                                <input type="text" class="form-control" name="tax_id_number" id="tax_id_number"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address">Dirección</label>
                                <input type="text" class="form-control" name="address" id="address" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city">Ciudad</label>
                                <select class="form-control" id="city" name="city" required>
                                    <option value="" selected readonly disabled>Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="max_rooms">Máx. Habitaciones</label>
                                <input type="number" class="form-control" name="max_rooms" id="max_rooms" min="1"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5>Habitaciones</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="room_type">Tipo de Habitación</label>
                                        <select class="form-control" name="room_type" id="room_type">
                                            <option value="" selected readonly disabled>Seleccione...</option>
                                            @foreach ($roomTypes as $roomType)
                                            <option value="{{ $roomType->id }}">
                                                {{ $roomType->type }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="accommodation">Acomodación</label>
                                        <select class="form-control" name="accommodation" id="accommodation">
                                            <option value="" selected readonly disabled>Seleccione...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quantity_unit">Cantidad</label>
                                        <input type="text" class="form-control" name="quantity_unit" id="quantity_unit">
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center mb-3">
                                <div class="col-md-4 text-center">
                                    <input type="button" class="btn btn-info text-center" id="btnAdd" value="Agregar">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered" id="roomsTable">
                                <thead>
                                    <tr>
                                        <th>Tipo Habitación</th>
                                        <th>Acomodación</th>
                                        <th>Cantidad</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>