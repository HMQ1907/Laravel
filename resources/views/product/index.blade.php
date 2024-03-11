@extends('layout.app')
<h1 class="text-center">ass</h1>
<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button class="btn btn-danger">Logout</button>
</form>
