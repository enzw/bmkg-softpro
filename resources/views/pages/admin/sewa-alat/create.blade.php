@extends('layouts.admin')

@section('content')
    @include('components.form-sewa-alat-admin', ['alats' => $alats, 'is_edit' => false])
@endsection