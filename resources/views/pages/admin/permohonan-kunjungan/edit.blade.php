@extends('layouts.admin')

@section('content')
    @include('components.form-permohonan-kunjungan-admin', ['permohonan' => $permohonan, 'is_edit' => true])
@endsection