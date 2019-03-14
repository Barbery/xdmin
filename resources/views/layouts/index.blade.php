@extends('layouts.admin-app')

@section('extraLink')
  <link rel="stylesheet" href="{{ asset('admin-assets/dist/css/index.css') }}" />
  <link rel="stylesheet" type="text/css" href="https://cdn.staticfile.org/bootstrap-table/1.12.1/bootstrap-table.min.css" />
@endsection

@section('content')
  <div class="row">
    <div class="col-xs-12">
      @yield('content')
    </div>
  </div>
@endsection

@section('extraScript')

@endsection
