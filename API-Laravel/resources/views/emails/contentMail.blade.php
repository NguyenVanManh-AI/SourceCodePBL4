<!DOCTYPE html>
<html>
<head>
    <title>Laravel 8 Send Email Example</title>
    <style>
        #div{
            display: flex;
            align-items: center;
            box-shadow: 0px 10px 10px -10px gray;
            border-radius: 16px;
            width: 400px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div id="div">
        <img src="https://lh3.googleusercontent.com/drive-viewer/AJc5JmRiOXu209g6Omk0BSgi-3d872qqlStrjpwB5CFpxU_YJ1lR9ufdPj3mb4K6fdEB66MKkgcUPKw=w1920-h866" width="80px">
        <h1 style="color:#0085FF;margin-left: 10px;margin-top:30px">Meta Shop</h1><br>
    </div>
    <p>You are receiving this email because we received a password reset request for your account.</p>
    <a href="{{$__url}}">{{$__url}}</a>
    <p>If you did not request a password reset, no further action is required.</p>
</body>
</html> 