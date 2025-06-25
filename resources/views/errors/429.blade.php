@extends('errors::minimal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', "Nous avons détecté un grand nombre de requêtes en peu de temps. Veuillez patienter quelques instants avant de tenter à nouveau.")

