@extends('adminlte::page')

{{-- Inyecta SweetAlert2 globalmente después de todo el JS del hijo --}}
@section('adminlte_js')
    @parent
    @include('partials.sweetalert')
@endsection
