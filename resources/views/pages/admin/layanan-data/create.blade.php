@extends('layouts.admin')

@section('content')
    @include('components.form-layanan-data-admin', ['is_edit' => false])
@endsection