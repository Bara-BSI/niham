@extends('errors::minimal')

@section('title', __('messages.too_many_requests'))
@section('code', '429')
@section('message', __('messages.too_many_requests'))
