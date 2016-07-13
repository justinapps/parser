<?php

if(isset($_POST['userInput'])){
    require("TextParser.class.php");
        
    $userInput = filter_var($_POST['userInput'], FILTER_SANITIZE_STRING);

    $textParser = new TextParser($userInput);

    $queryTest = $textParser->getQuery();
    
    $apiResults = $textParser->getResults(); 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.min.css">   

</head>

<body>    
    <div class="container">
        
        <form class="col s12" role="form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="input-field col s12">
                <input type="text" class="form-control" name="userInput" id="userInput" value="<?php if(isset($userInput)){echo $userInput;} ?>">
                <label for="userInput">Enter your search sentence here.</label>
            </div>

            <button class="btn waves-effect waves-light" type="submit" name="action">Submit</button>
        </form>
        
        <?php if(!empty($queryTest)): ?>
            <h3>Supplied Search Parameters</h3>
            <?php print_r($queryTest) ?>

            <h3>API Response Results</h3>
            <?php print_r($apiResults) ?>
        <?php endif; ?>
                        
    </div>

    <!-- Compiled and minified JavaScript -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/js/materialize.min.js"></script>
</body>
</html>