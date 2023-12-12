<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Регистрация - {{$registrationEmailDto->olympiadName}}</title>
</head>
<body>

<p>Ваша команда: {{$registrationEmailDto->teamName}} теперь является участником "{{$registrationEmailDto->olympiadName}}"</p>
<p><br></p>
<p>Чтобы подтвердить участие и окончить регистрацию, нажмите на ссылку {{$registrationEmailDto->registrationUrl}}</p>
<p><br></p>
<p>Данные об участниках команды:</p>
@foreach($registrationEmailDto->participants as $participant)
    <p>ФИО участника: {{$participant->name}}</p>
    <p>Логин участника: {{$participant->login}}</p>
    <p>Пароль участника: {{$participant->password}}</p>
    <p><br></p>
@endforeach
</body>
</html>
