<!DOCTYPE html>
<html >
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>صفحه ی ورود</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link href="{{ URL::asset('public/login/css/style.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('public/dashboard/css/bootstrap.min.css')}}" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
<div class="wrapper">
   @yield('content')
<<<<<<< HEAD
<<<<<<< HEAD
    <footer><a style="font-size:20px;" target="blank" href="http://www.artansoftware.ir">طراحی شده توسط گروه فنی مهندسی آرتان</a></footer>
=======
    <footer><a target="blank" href="http://www.artansoftware.com">Designed By Artan Group</a></footer>
>>>>>>> 6b1f09f35b8b7ee4ef8f191c1bfd76c2f8b84916
=======
    <footer><a style="font-size:20px;" target="blank" href="http://www.artansoftware.ir">طراحی شده توسط گروه فنی مهندسی آرتان</a></footer>
>>>>>>> cf993fe6cc4c3ceeeec0356703cd1e914d71e7be
    </p>
</div>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
{{--<script  src="{{URL::asset('public/login/js/index.js')}}"></script>--}}
</body>
</html>
