<!doctype html>
<html>
<head>
    @include('includes.head')
    @livewireStyles
</head>
<body>
   

    <header class="row">
        @include('includes.header')
    </header>

    <div>
   
            @yield('content')
            @livewireScripts
            @include('includes.footer')

    </div>

   
       
  


     


</body>
</html>