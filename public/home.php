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

    <?php 
    
    $model = new User();

    ?>


    <!-- SELECT -->
    <div>
        <?php 
            print_r($model->get([
                "username" => "aorikasumi",
                "password" => "aorikasumi",
            ])); 
        ?>
    </div>


    <!-- INSERT -->
    <div>
        <?php 
            // print_r($model->store([
            //     "username" => "fdsfdsf",
            //     "password" => "fdf"
            // ])); 
        ?>
    </div>


    <!-- UPDATE -->
    <div>
        <?php 
            // print_r($model->edit(
            //     "68cac08bb8459",
            //     [
            //         "username" => "fdsfdsf",
            //         "password" => "fdf"
            //     ]
            // )); 
        ?>
    </div>
</body>
</html>
