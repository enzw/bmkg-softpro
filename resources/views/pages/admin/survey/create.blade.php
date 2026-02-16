@extends('layouts.admin')

@section('content')
    @include('components.form-survey-admin', ['is_edit' => false])
@endsection