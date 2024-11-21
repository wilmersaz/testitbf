@extends('layouts.app')

@section('content')
@section('js')
<div class="container">
    <h1>Tipos de Habitaciones</h1>
    <button class="btn btn-primary" data-toggle="modal" data-target="#roomTypeModal">Agregar Tipo de Habitación</button>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roomTypes as $roomType)
            <tr>
                <td>{{ $roomType->id }}</td>
                <td>{{ $roomType->type }}</td>
                <td>
                    <button class="btn btn-warning" onclick="editRoomType({{ $roomType->id }})">Editar</button>
                    <button class="btn btn-danger" onclick="deleteRoomType({{ $roomType->id }})">Eliminar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@include('room-types.form')

<script>

    $('#roomTypeModal').on('hide.bs.modal', function() {
        $('#roomTypeModalLabel').text('Agregar Tipo de Habitación');
        $('#roomTypeForm')[0].reset();
    });

    function editRoomType(id) {
        // llamada AJAX para obtener los datos del tipo de habitación y llenar el formulario
        $.ajax({
            url: '/room-types/' + id + '/edit',
            method: 'GET',
            success: function(data) {
                $('#id').val(data.id);
                $('#type').val(data.type);
                $('#roomTypeModalLabel').text('Editar Tipo de Habitación');
                $('#roomTypeModal').modal('show');
            }
        });
    }

    // Manejo del formulario (crear o actualizar)
    $('#roomTypeForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#id').val();
        let url = id ? '/room-types/' + id : '/room-types';
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

    // Función para eliminar tipo de habitación
    window.deleteRoomType = function(id) {
        swal.fire({
            title: "¿Estás seguro de eliminar este registro?",
            icon: "warning",
            showCancelButton: !0,
            cancelButtonText: "Cancelar",
            confirmButtonText: "Si"
        }).then(function (e) {
            if (e.value) {
                $.ajax({
                    url: '/room-types/' + id,
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
</script>
@endsection