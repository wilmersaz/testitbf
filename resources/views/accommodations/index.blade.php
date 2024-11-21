@extends('layouts.app')

@section('content')
@section('js')
<div class="container">
    <h1>Acomodaciones</h1>
    <button class="btn btn-primary" data-toggle="modal" data-target="#accommodationModal">Agregar Acomodación</button>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Acoodación</th>
                <th>Tipo de Habitación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accommodations as $accommodation)
            <tr>
                <td>{{ $accommodation->id }}</td>
                <td>{{ $accommodation->accommodation }}</td>
                <td>{{ $accommodation->roomType?->type }}</td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-warning"
                            onclick="editAccommodation({{ $accommodation->id }})">Editar</button>
                        <button class="btn btn-danger"
                            onclick="deleteAccommodation({{ $accommodation->id }})">Eliminar</button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include('accommodations.form')

<script>
    $(document).ready(function() {

    $('#accommodationModal').on('hide.bs.modal', function() {
        $('#accommodationModalLabel').text('Agregar Acomodación');
        $('#accommodationForm')[0].reset();
        $('#accommodationForm select').val('').change();
    });

    // Función para llenar el formulario al editar acomodación
    window.editAccommodation = function(id) {
        $.ajax({
            url: '/accommodations/' + id + '/edit',
            method: 'GET',
            success: function(data) {
                $('#id').val(data.id);
                $('#room_type_id').val(data.room_type_id); // Seleccionar el tipo de habitación
                $('#accommodation').val(data.accommodation);
                $('#accommodationModalLabel').text('Editar Acomodación');
                $('#accommodationModal').modal('show');
            }
        });
    };

    // Manejo del formulario (crear o actualizar)
    $('#accommodationForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#id').val();
        let url = id ? '/accommodations/' + id : '/accommodations';
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

    // Función para eliminar acomodación
    window.deleteAccommodation = function(id) {
        swal.fire({
            title: "¿Estás seguro de eliminar este registro?",
            icon: "warning",
            showCancelButton: !0,
            cancelButtonText: "Cancelar",
            confirmButtonText: "Si"
        }).then(function (e) {
            if (e.value) {
                $.ajax({
                    url: '/accommodations/' + id,
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
});
</script>
@endsection