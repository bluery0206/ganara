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

    <div>
        <?php print_r($model->get()); ?>
    </div>
</body>
</html>