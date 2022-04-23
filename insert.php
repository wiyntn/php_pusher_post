<?php
    
    require_once 'database.php';
    require __DIR__ . '/vendor/autoload.php';

    if (isset($_POST['first_name']) && isset($_POST['last_name'])) {
        
        $first_name = $_POST['first_name'];
        $last_name  = $_POST['last_name'];
        $created_at = date("Y-m-d H:i:s");

        $sql = "INSERT INTO users (first_name, last_name, created_at) VALUES ('$first_name', '$last_name', '$created_at')";
        $query = $db->query($sql);
        if ($query) {
            $options = array(
                'cluster' => 'ap1',
                'useTLS' => true
              );
              $pusher = new Pusher\Pusher(
                '34fc900260dd4920a817',
                '213abf8be874bf04042f',
                '1400112',
                $options
              );
        
            $data['user_id'] = $db->insert_id;
            $data['message'] = "$first_name $last_name successfully created!";

            $pusher->trigger('php_pusher_post', 'my-event', $data);
            // $pusher->trigger('createChannel', 'createTable', $data); // CAN BE MULTIPLE TRIGGER

            header("Location: create.php");
        } else {
            echo $db->error;
        }
    }

?>