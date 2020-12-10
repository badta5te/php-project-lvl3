@extends('layouts.app')

@section('content')
    <h1>Domains</h1>
    @foreach ($domains as $domain)
    <div class="container">
        <div class="row">
            <div class="col">
                {{ $domain->name }}
            </div>
            <div class="col">
                {{ $domain->id }}
            </div>
        </div>
    </div>
    @endforeach
@endsection
