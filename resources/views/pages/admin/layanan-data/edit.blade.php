@extends('layouts.admin')

@section('content')
    @include('components.form-layanan-data-admin', ['is_edit' => true, 'permohonan' => $permohonan])
@endsection