<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Today, a year ago</title>
    <link href="style.css" rel="stylesheet">
    <!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#/">Today, a year ago</a>
            </div>
        </div>
    </nav>

    <div id="container" class="container">
        <?php
			spl_autoload_register(function ($classname) {
				require("../app/".$classname .".php");
			});
            $app = new app;
            echo $app->run();
        ?>
    </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p class="text-center">
                        <small>Generated with <a href="https://github.com/engelju/today-a-year-ago">Today, a year ago</a> created by <a href="https://github.com/engelju">jeng</a>.</small>
                    </p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>