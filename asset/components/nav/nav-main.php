<nav>
    <div class="brand">
        BoilerPlate
    </div>
    <div class="menu">
        <a href="<?= route("home") ?>" class="btn btn-nav <?= isViewActive("home") ?>">Home</a>
        <a href="<?= route("auth/login") ?>" class="btn btn-nav <?= isViewActive("auth/login") ?>">Login</a>
    </div>
</nav>