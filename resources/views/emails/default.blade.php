<html>
    <head>
        <style>
            body{
                width:100%; 
                height:100vh; 
                background-color:#fafafa;
                padding:0;
                margin:0;
                font-family:\'HelveticaNeue\',\'Helvetica Neue\',Helvetica,Arial,sans-serif;
            }
            #container{
                width:500px; 
                margin:15px auto 0px auto; 
                border:1px solid #ccc;
                background-color:#fff;
                padding:20px 30px;
                border-width:3px 1px;
                border-top-color:#fe8900;
                border-bottom-color:#feb500;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <a href="https://digitalcrm.com/"><center>{{$title}}</center></a>
            <div>
                {!!$content!!}
            </div>
        </div>
    </body>
</html>