@extends('core.layouts.layout-login')

@section('scripts')
    <script src="/assets/js/sessions/login.js"></script>
@stop

@section('content')
    @include('core.sessions.partials.login-form')
@stop
