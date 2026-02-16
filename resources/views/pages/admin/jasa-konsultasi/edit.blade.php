@extends('layouts.admin')

@section('content')
    @include('components.form-jasa-konsultasi-admin', ['is_edit' => true, 'permohonan' => $permohonan])
@endsection