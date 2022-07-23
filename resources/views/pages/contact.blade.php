@extends('layouts.default')
@section('content')
    
    
    <body class="bg-hero-pattern" >

       <livewire:message-form></livewire:message-form>



        {!! NoCaptcha::renderJs() !!}
    </body>
        
@stop   