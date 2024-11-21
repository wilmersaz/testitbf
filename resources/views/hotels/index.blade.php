@extends('layouts.app')
@section('content')
@section('js')
<div class="container">
    <h1>Hoteles</h1>
    <button class="btn btn-primary" data-toggle="modal" data-target="#hotelModal">Agregar Hotel</button>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Nit</th>
                <th>Dirección</th>
                <th>Ciudad</th>
                <th>Máx. Habitaciones</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hotels as $hotel)
            <tr>
                <td>{{ $hotel->id }}</td>
                <td>{{ $hotel->name }}</td>
                <td>{{ $hotel->tax_id_number }}</td>
                <td>{{ $hotel->address }}</td>
                <td>{{ $hotel->city }}</td>
                <td>{{ $hotel->max_rooms }}</td>
                <td>
                    <button class="btn btn-warning" onclick="editHotel({{ $hotel->id }})">Editar</button>
                    <button class="btn btn-danger" onclick="deleteHotel({{ $hotel->id }})">Eliminar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@include('hotels.form')

<script>
    var delete_row = [];
    $(document).ready(function() {
        // Realizar la llamada AJAX para consultar ciudades en la divipola
        $.ajax({
            url: 'https://www.datos.gov.co/resource/gdxc-w37w.json',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Limpiar el select
                $('#city').empty();
                $('#city').append('<option value="" selected readonly disabled>Seleccione...</option>');

                // Llenar el select con los datos recibidos
                $.each(data, function(index, item) {
                    $('#city').append('<option value="' + item.nom_mpio + ' - ' + item.dpto + '">' + item.nom_mpio + ' - ' + item.dpto + '</option>');
                });
            },
            error: function() {
                $('#city').empty();
                $('#city').append('<option value="">Error al cargar datos</option>');
            }
        });

        $('#room_type').change(function() {
            var roomTypeId = $(this).val(); // Obtener el ID del tipo de habitación seleccionado

            // Limpiar el select de acomodaciones
            $('#accommodation').empty();
            $('#accommodation').append('<option value="" selected readonly disabled>Seleccione...</option>');

            // Realizar la llamada AJAX
            $.ajax({
                url: '/accommodations/' + roomTypeId,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Limpiar el select de acomodaciones
                    $('#accommodation').empty();
                    $('#accommodation').append('<option value="" selected readonly disabled>Seleccione...</option>');

                    // Llenar el select con los datos recibidos
                    $.each(data, function(index, item) {
                        $('#accommodation').append('<option value="' + item.id + '">' + item.accommodation + '</option>');
                    });
                },
                error: function() {
                    $('#accommodation').empty();
                    $('#accommodation').append('<option value="" selected readonly disabled>Seleccione...</option>');
                }
            });
        });

    });

    $('#hotelModal').on('hide.bs.modal', function() {
        $('#hotelModalLabel').text('Agregar Hotel');
        $('#hotelForm')[0].reset();
        $('#hotelForm select').val('').change();
    });

    function editHotel(id) {
        // llamada AJAX para obtener los datos del hotel y llenar el formulario
        $.ajax({
            url: '/hotels/' + id + '/edit',
            method: 'GET',
            success: function(data) {
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#tax_id_number').val(data.tax_id_number);
                $('#address').val(data.address);
                $('#city').val(data.city);
                $('#max_rooms').val(data.max_rooms);

                data.rooms.forEach(room => {
                    $('#roomsTable tbody').append(
                    `<tr>
                    <td><input type='hidden' name='room_type_id[]' value='${room.room_type_id}'>${room.room_type.type}</td>
                    <td><input type='hidden' name='accommodation_id[]' value='${room.accommodation_id}'>${room.accommodation.accommodation}</td>
                    <td><input type='hidden' name='quantity[]' value='${room.quantity}'>${room.quantity}</td>
                    <td><div class='btn-group'>
                        <button class='btn btn-icon btn-danger btn-sm deleteRow' title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div></td>
                </tr>`
                );
                });

                $('#hotelModalLabel').text('Editar Hotel');
                $('#hotelModal').modal('show');
            }
        });
    }

    // Manejo del formulario (crear o actualizar)
    $('#hotelForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#id').val();
        let url = id ? '/hotels/' + id : '/hotels';
        let method = id ? 'PUT' : 'POST';

        $.ajax({url: url,type: method,dataType: 'json',data: $(this).serialize(),
            success: function (res) {
                swal.fire(res.title ?? '¡Error!', res.message ?? 'Ha ocurrido un error desconocido', res.icon ?? 'error');
                setTimeout(function() {
                    location.reload();
                }, 1000); // Recargar la página despues de 1 segundo para ver los cambios
            },
            error: function (res) {
                if (res.responseJSON?.errors) {
                    var lista = "<ul>";
                    jQuery.each(res.responseJSON?.errors, function (key, value) {
                        lista += "<li>" + value + "</li>";
                    });
                    swal.fire('Campos requeridos',lista + "</ul>",'error');
                } else {
                    swal.fire(res.responseJSON?.title ?? '¡Error!', res.responseJSON?.message ?? 'Ha ocurrido un error desconocido', res.responseJSON?.icon ?? 'error');
                }
            }
        });

    });

    // Función para eliminar el hotel
    window.deleteHotel = function(id) {
        swal.fire({
            title: "¿Estás seguro de eliminar este registro?",
            icon: "warning",
            showCancelButton: !0,
            cancelButtonText: "Cancelar",
            confirmButtonText: "Si"
        }).then(function (e) {
            if (e.value) {
                $.ajax({
                    url: '/hotels/' + id,
                    type: 'DELETE',
                    data: { id: id, _token: $('input[name=_token]').val() },
                    dataType: 'json',
                    success: function (res) {
                        swal.fire(res.title ?? '¡Error!', res.message ?? 'Ha ocurrido un error desconocido', res.icon ?? 'error');
                        setTimeout(function() {
                            location.reload();
                        }, 1000); // Recargar la página despues de 1 segundo para ver los cambios
                    },
                    error: function (res) {
                        if (res.responseJSON?.errors) {
                            var lista = "<ul>";
                            jQuery.each(res.responseJSON?.errors, function (key, value) {
                                lista += "<li>" + value + "</li>";
                            });
                            swal.fire('Campos requeridos',lista + "</ul>",'error');
                        } else {
                            swal.fire(res.responseJSON?.title ?? '¡Error!', res.responseJSON?.message ?? 'Ha ocurrido un error desconocido', res.responseJSON?.icon ?? 'error');
                        }
                    }
                })
            }
        });
    };

    $(document).on('click', '#btnAdd', function () {
            let room_type_id = $('#room_type').val();
            let room_type_idText = $('#room_type option:selected').text();
            let accommodation_id = $("#accommodation").val();
            let accommodation_idText = $('#accommodation option:selected').text();
            let quantity = $('#quantity_unit').val();
            var lista = "<ul>";
            if (room_type_idText == 'Seleccione...' || room_type_idText == '' || room_type_idText == null)
                lista += "<li>Tipo de Habitación</li>";
            if (accommodation_idText == 'Seleccione...' || accommodation_idText == '' || accommodation_idText == null)
                lista += "<li>Acomodación</li>";
            if (quantity == '' || quantity == null)
                lista += "<li>Cantidad</li>";

            lista += "</ul>";

            if (lista.length > 11) {
                Swal.fire('Información Incompleta', '<strong>Los siguientes campos no pueden ir vacíos:</strong> <br><br>' + lista, 'error')
                return;
            }

            let filas = $("#roomsTable").find("tr");
            for (let i = 0; i < filas.length; i++) {
                let celdas = $(filas[i]).find("td");
                    if (
                        ($(celdas[0]).text() == room_type_idText
                            && $(celdas[1]).text() == accommodation_idText)
                    ) {
                        Swal.fire("Registro ya en tabla", "Ya se encuentra en la tabla un registro con los mismos datos", "warning");
                        return false;
                    }
            }
                $('#roomsTable tbody').append(
                    `<tr>
                    <td><input type='hidden' name='room_type_id[]' value='${room_type_id}'>${room_type_idText}</td>
                    <td><input type='hidden' name='accommodation_id[]' value='${accommodation_id}'>${accommodation_idText}</td>
                    <td><input type='hidden' name='quantity[]' value='${quantity}'>${quantity}</td>
                    <td><div class='btn-group'>
                        <button class='btn btn-icon btn-danger btn-sm deleteRow' title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div></td>
                </tr>`
                );

            $("#room_type,#accommodation").val(null).trigger('change');
            $('#quantity_unit').val(null)
        });

        $(document).on('click', '.deleteRow', function (e) {
            if (e.isDefaultPrevented()) {
            } else {
                e.preventDefault();
                var index = $(this).closest("tr").index()
                delete_row.splice(index, 1);
                $(this).closest("tr").remove();
            }
        });
    
</script>
@endsection