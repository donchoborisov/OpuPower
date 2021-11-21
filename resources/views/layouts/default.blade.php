<!doctype html>
<html>
<head>
    @include('includes.head')
</head>
<body>
   

    <header class="row">
        @include('includes.header')
    </header>

    <div>
   
            @yield('content')
            @include('includes.footer')

    </div>

   
       
  


     


</body>
</html>