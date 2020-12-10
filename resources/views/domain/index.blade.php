@extends('layouts.app')

@section('content')
    <table class="table table-borderless">
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
                <td>{{ $domain->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
