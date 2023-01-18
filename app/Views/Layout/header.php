<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Php Object</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link active" aria-current="page" href="/">Home</a>
                    <a class="nav-link" href="/postes">Postes</a>
                </div>
                <div class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user']) && !empty($_SESSION['user']['id'])) : ?>
                        <a href="/user/logout" class="btn btn-danger">Déconnexion</a>
                    <?php else : ?>
                        <a href="/user/login" class="btn btn-light">Connexion</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>