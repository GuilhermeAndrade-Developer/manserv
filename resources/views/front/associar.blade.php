@extends("templates.template")

@section("css")
    <link rel="stylesheet" media="screen" href="{{ asset('/assets/css/associar.css') }}">
@endsection

@section("content")
<div class="container tab-pane active">
    <div class="table-wrapper">
        <div class="table-title">
            <div class="row">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6" style="text-align: right; align-items: flex-end; margin-bottom: 10px;">
                    <button type="button" class="btn btn-success" onclick="associarUsuario()">
                        <i class="material-icons">&#xE147;</i> <span>Associar Usuário a UT</span>
                    </button>
                </div>
            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>UT Associada</th>
                </tr>
            </thead>
            <tbody id="listaUts">

            </tbody>
        </table>
    </div>
</div>
@include('front.includes.modal.modal_associar');
@endsection

@section("js")
<script src="{{ asset('/assets/js/actions.js') }}"></script>
<script src="{{ asset('/assets/js/associarUsuario.js') }}"></script>
@endsection
