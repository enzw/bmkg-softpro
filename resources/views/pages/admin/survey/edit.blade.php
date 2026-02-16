@extends('layouts.admin')

@section('content')
    @include('components.form-survey-admin', ['permohonan' => $permohonan, 'is_edit' => true])
@endsection