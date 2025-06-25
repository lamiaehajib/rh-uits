@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', "Une erreur inattendue s'est produite sur le serveur. Nous travaillons à résoudre ce problème. Veuillez réessayer plus tard.")


