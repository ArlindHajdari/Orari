<h3>Përshëndetje {{$user->academic_title}}{{$user->first_name}} {{$user->last_name}}</h3>

<p>Për të ndryshuar fjalëkalimin ju lutem klikoni në vegëzën më poshtë:</p><br>
<a href="http://localhost:8000/reset/{{$user->email}}/{{$code}}">Kliko këtu!</a>
