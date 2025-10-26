<?php 

include "../../bootstrap.php";

use App\Models\User;
use App\Core\Validator;
use App\Controllers\AuthController;

$pageTitle = "Register";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // AuthController::register($username, $password);
}

?>

<!DOCTYPE html>
<html lang="en">

<!-- <head> -->
<?php include asset("components/head.php") ?>
 
<body>
    <?php include asset("components/nav/navSample.php") ?>

    
    <div class="form-wrapper">
        <form class="form form-login" method="POST">
            <script src="<?= asset("js/toggleEye.js"); ?>"></script>

            <div class="form-header">
                <h2>Register</h2>
            </div>
            
            <div class="form-body">
                <div class="form-group">
                    <div class="form-field">
                        <label class="form-label" for="username">Username</label>
                        <input class="form-input" type="text" name="username" id="username" placeholder="Ex.: juan.delacruz" required>
                    </div>
                
                    <div class="form-field">
                        <label class="form-label" for="password">Password</label>
                        <div class="form-eye">
                            <input class="form-eye-input" type="password" name="password" id="password" placeholder="Ex.: jU4nd3lAcRuZ" required>
                            
                            <!-- toggleEye(tagId, imgId, basePath) -->
                            <button type="button" class="form-eye-toggle" onclick="toggleEye('password', 'passwordEyeIcon', '<?= asset('images/svg/') ?>')">
                                <img id="passwordEyeIcon" class="form-eye-icon" src="<?= asset("images/svg/visible.svg") ?>" alt="visible icon">
                            </button>
                        </div>
                    </div>

                    <div class="form-field">
                        <label class="form-label" for="passwordConfirm">Confirm Password</label>

                        <div class="form-eye">
                            <input class="form-eye-input" type="password" name="passwordConfirm" id="passwordConfirm" placeholder="Ex.: jU4nd3lAcRuZ" required>

                            <!-- toggleEye(tagId, imgId, basePath) -->
                            <button type="button" class="form-eye-toggle" onclick="toggleEye('passwordConfirm', 'passwordConfirmEyeIcon', '<?= asset('images/svg/') ?>')">
                                <img id="passwordConfirmEyeIcon" class="form-eye-icon" src="<?= asset("images/svg/visible.svg") ?>" alt="visible icon">
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-footer">
                <button class="btn btn-primary" type="submit">
                    Register
                </button>
                <a class="btn" href="<?= route("auth/login") ?>">
                    Login
                </a>
            </div>
        </form>
    </div>
</body>
</html>
