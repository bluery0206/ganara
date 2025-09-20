<?php 

use App\Core\Enums\FetchOption;
use App\Models\User;

include "../bootstrap.php";

$pageTitle = "Home";

?>

<!DOCTYPE html>
<html lang="en">

<!-- <head> -->
<?php include asset("components/head.php") ?>

<body>
    <!-- Navigation Bar -->
    <?php include asset("components/nav/nav-main.php") ?>

    <?php $model = new User(); ?>


    <!-- SELECT -->
    <div>
        <?php 
            $users = $model->like([
                    "username" => "aori"
                ],
                extras: [
                    "groupBy" => 'username',
                    "orderBy" => ["username", "ASC"],
                    "limit" => 10
                ],
                fetchOption: FetchOption::FETCH_ALL
            );

            if (is_array($users)) {
                foreach ($users as $user) {
                    echo "USERNAME: $user->username<BR>";
                    echo "PASSWORd: $user->password<BR>";
                    echo "<BR>";
                }
            }
            else {
                print_r($users);
            }
        ?>
    </div>


    <!-- INSERT -->
    <div>
        <?php
            // $ran = uniqid();

            // $res = $model->store([
            //     // "username" => $ran,
            //     // "password" => $ran,
            //     "username" => "",
            //     "password" => "",
            // ]);

            // echo $res->username;
        ?>
    </div>


    <!-- UPDATE -->
    <div>
        <?php 
            // $res = $model->edit("68cb7259f1483", [
            //     "username" => "fiona.young",
            //     "password" => "fiona.young",
            // ]);

            // var_dump($res); 
        ?>
    </div>


    <!-- DELETE -->
    <div>
        <?php 
            // $res = $model->delete("68cb7c052dff6");

            // var_dump($res); 
        ?>
    </div>


    <!-- DELETE ALL WITH CONDITION -->
    <div>
        <?php
            // $res = $model->deleteAll(["isJudge" => 1]);

            // var_dump($res); 
        ?>
    </div>


    <div>
        <?php

            // Dummy data
            // $data = [
            //     "username" => "dd",
            //     "email" => "fiona.young@gmail.com",
            //     "password" => "fiona.young",
            // ];

            // $violations = Validator::validate($model, $data);
            // echo "Violations: <BR>";
            // var_dump($violations);
        ?>
    </div>
</body>
</html>
