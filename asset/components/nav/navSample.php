<nav>
    <div class="brand">
        Ganara
    </div>
    <div class="menu">
        <!-- Show the home button only if logged in -->
        <?php if (isAuthorized()) { ?>
            <a href="<?= route("examples") ?>" 
                class="btn btn-nav <?= isViewActive("examples") ?>">
                Home
            </a>
        <?php } else { ?>
            <a href="<?= route("auth/login") ?>" 
                class="btn btn-nav <?= isViewActive("auth/login") ?>">
                Login
            </a>
            <a href="<?= route("auth/register") ?>" 
                class="btn btn-nav <?= isViewActive("auth/register") ?>">
                Register
            </a>
        <?php } ?>
    </div>
</nav>