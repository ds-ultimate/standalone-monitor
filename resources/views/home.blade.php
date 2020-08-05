@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Server Übersicht <a class="btn btn-outline-dark float-right btn-sm" href="#" onclick="alert('Funktion existiert nicht')" role="button" title="Add Server"><i class="fas fa-plus"></i></a></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                        @foreach($servers as $server)
                            @include('components.home.serverCard')
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
