@extends('layouts.app')

@section('content')
    <table class="table table-bordered table-hover text-nowrap">
        <thead>
        <tr>
            <th scope="col">Id</th>
            <th scope="col">Name</th>
        </tr>
        </thead>
        <tbody>
        @foreach($domains as $domain)
            <tr>
                <td>{{ $domain->id }}</td>
                <td><a href="{{ route('domains.show', $domain->id) }}">{{ $domain->name }}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
