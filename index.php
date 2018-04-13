<html>
<head lang="en">
    <title>UNM Project</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
</head>
<body class="blue lighten-3">
    <div id="login-container" class="container white" style="margin-top: 15vh; padding: 2%">
        <div class="row center ">
            <h3>Log In!</h3>
            <form id="login-form" action="/php/UserRest.php" method="post">
                <p id="form-error" class="hide red-text">The username or password was invalid, please try again!</p>
                <div class="input-field col s12">
                    <input id="userUsername" name="userUsername" type="text" class="validate">
                    <label for="userUsername">UserName</label>
                </div>
                <div class="input-field col s12">
                    <input id="userPassword" name="userPassword" type="password" class="validate">
                    <label for="userPassword">Password</label>
                </div>
                <button id="login-button" class="btn-large" type="submit">ENTER</button>
            </form>
        </div>
    </div>

    <div id="schedule-container" class="container white hide center" style="margin-top: 15vh; padding: 2%">
        <h3>Schedule Items!</h3>
        <div class="row">
            <div class="col s12">
                <ul id="initial" class="collapsible">

                </ul>
                <a id="logout" class="btn">Log Out</a>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>

