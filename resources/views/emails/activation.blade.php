<h1>Përshëndetje {{$user->first_name}} {{$user->last_name}}</h1>
<p>
    Për të aktivizuar llogarinë tuaj, klikoni linkun me poshtë.
</p>
<br>
<a href="{{env('APP_URL')}}/activate/{{$user->email}}/{{$code}}">Vegëza e aktivizimit!</a>