<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        @if($flag == 'registered')
            <p><b>{{$user->first_name.' '.$user->last_name}}</b></p>
            <div>
                <p>Congratulations your have registered an account with <b>Goseeum</b> successfully</p>
                <p>Here are your details</p>
                <p><b>Name : </b> {{$user->first_name.' '.$user->last_name}}</p>
                <p><b>Email : </b> {{$user->email}}</p>
                <p><b>Gender : </b> {{$user->gender}}</p>
            </div>
        @endif
    	<br><br>
		<p><strong>Need help?</strong> click <a href="{{url('/')}}">here</a>.</p>
		<p>Yours sincerely,<br />
		<strong>The Goseeum Team</strong></p>
    </body>
</html>
