@extends('retenciones.layout')
@section('retencionesContent')


<div class="row justify-content-center">
    <div class="col-auto">
        <form method="POST" id="excel" action="{{route('retenciones.postExcel')}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Subir excel de datos</label>
                <input type="file" class="form-control-file" id="file" name="file">
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        Subir
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection
