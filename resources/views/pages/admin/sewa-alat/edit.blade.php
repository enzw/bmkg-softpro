@extends('layouts.admin')

@section('content')
    @include('components.form-sewa-alat-admin', ['alats' => $alats, 'permohonan' => $permohonan, 'is_edit' => true])
@endsection