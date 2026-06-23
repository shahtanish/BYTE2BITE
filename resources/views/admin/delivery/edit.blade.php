@extends('layouts.admin')
@section('page_title','Delivery Partner')
@section('content')
<div class="card"><div class="card-body"><a href="{{ route('admin.delivery-partners.index') }}" class="btn btn-sm" style="background:#f0f0f0;color:#555;border-radius:5px;">← Back to Partners</a><p class="mt-3" style="color:#888;">This page loads from the controller.</p></div></div>
@endsection
