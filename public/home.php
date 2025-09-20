<?php 

use App\Models\User;
use App\Core\BaseModel;
use App\Core\Validator;

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
        //     $user = $model->get([
        //         "username" => "clark.namay",
        //         "password" => "clark.namay",
        //     ]);
        // ?>
    </div>


    <!-- INSERT -->
    <div>
        <?php
            // $ran = uniqid();

            // $res = $model->store([
            //     "username" => $ran,
            //     "password" => $ran,
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
$data = [
    "username" => "dd",
    "email" => "fiona.young@gmail.com",
    "password" => "fiona.young",
];



$violations = Validator::validate($model, $data);
echo "Violations: <BR>";
var_dump($violations);

        ?>
    </div>
</body>
</html>
