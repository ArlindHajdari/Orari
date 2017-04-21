<h3>Hello {{$user->first_name}} {{$user->last_name}}</h3>

<p>Please click the following link to reset your password:</p><br>
<a href="http://localhost:8000/reset/{{$user->email}}/{{$code}}">Click here!</a>
