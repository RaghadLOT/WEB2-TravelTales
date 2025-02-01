
        <?php
        // Display errors (for debugging)
        ini_set('display_errors', ' 1'); // Need to close after developing
        ini_set('log_errors', '1');
        error_reporting(E_ALL);

// Database connection
        $host = 'localhost';
        $db_user = 'root';
        $db_password = 'root';
        $db_name = 'travel_app';
        
        $connection = mysqli_connect($host, $db_user, $db_password, $db_name);
        
// Handling connection errors
        $error = mysqli_connect_error();

        if ($error != null) {
            $output = "<p> unable to connect</p>" . $error;
            exit($output);
        } 
        ?>
    