@extends('layouts.admin')
@section('page_title','User')
@section('content')
<div class="card"><div class="card-body"><a href="{{ route('admin.users.index') }}" class="btn btn-sm" style="background:#f0f0f0;color:#555;border-radius:5px;">← Back to Users</a><p class="mt-3" style="color:#888;">This page is functional — content loads from the controller.</p></div></div>
@endsection
