@extends('layouts.admin')

@section('content')
    @include('components.form-klaim-asuransi-admin', ['is_edit' => true, 'permohonan' => $permohonan])
@endsection