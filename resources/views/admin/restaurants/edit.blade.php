@extends('layouts.admin')
@section('page_title','Restaurant')
@section('content')
<div class="card"><div class="card-body"><a href="{{ route('admin.restaurants.index') }}" class="btn btn-sm" style="background:#f0f0f0;color:#555;border-radius:5px;">← Back to Restaurants</a><p class="mt-3" style="color:#888;">This page loads from the controller.</p></div></div>
@endsection
