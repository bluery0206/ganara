<?php 

use App\Core\Validator;
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


    <!-- DELETE ALL WITH CONDITION -->
    <div>
        <?php



$inputData = [
    "username" => "",
    "password" => "fiona.young",
];

$rules = $model->rules;

echo "<BR>input: "; var_dump($inputData);
echo "<BR>rules: "; var_dump($rules);

$inputKeys = array_keys($inputData);
$inputValues = array_values($inputData);

$rulesKeys = array_keys($rules);
$rulesValues = array_values($rules);

checkWrongKeys($rulesKeys, $inputKeys);

foreach ($inputKeys as $input) {
    echo "<BR>INPUT: {$input}";
    echo "<BR>RULES: "; var_dump($rules[$input]);

    foreach ($rules[$input] as $rule => $value) {
        echo "<BR>{$rule} => {$value} ||||| ";

        switch ($rule) {
            case 'min':
                echo "min: " . Validator::min($value, $inputData[$input]);
                break;
            case 'max':
                echo "max: " . Validator::max($value, $value);
                break;
            default:
                # code...
                break;
        }
    }
}



        ?>
    </div>
</body>
</html>
