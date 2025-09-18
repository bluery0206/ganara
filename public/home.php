<?php 

use App\Models\User;

include "../bootstrap.php";

$pageTitle = "Home";

?>

<!DOCTYPE html>
<html lang="en">

<!-- <head> -->
<?php include asset("components/head.php") ?>

<body>
    <?php include asset("components/nav/nav-main.php") ?>

    <?php $model = new User(); ?>


    <!-- SELECT -->
    <div>
        <?php 
            // print_r($model->get([
            //     "username" => "aorikasumi",
            //     "password" => "aorikasumi",
            // ])); 
        ?>
    </div>


    <!-- INSERT -->
    <div>
        <?php
            // $random = rand();
            // $res = $model->store([
            //     "username" => $random,
            //     "password" => $random
            // ]);

            // var_dump($res);
        ?>
    </div>


    <!-- UPDATE -->
    <div>
        <?php 
            // $res = $model->edit("68cb61d154f3a", [
            //     "username" => "fiona.young",
            //     "password" => "fiona.young",
            // ]);

            // var_dump($res); 
        ?>
    </div>


    <!-- DELETE -->
    <div>
        <?php 
            // $res = $model->delete("68cb61d154f3a");

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
</body>
</html>
