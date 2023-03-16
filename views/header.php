
<nav class="navvbar">
    <div class="navvbar-container">
        <div>
            <img class="logo" src="/check-your-mood/images/CheckYourMoodLogo.png" alt="Logo Check Your Mood">
        </div>
        <input type="checkbox" name="" id="">
        <div class="hamburger-lines">
            <span class="line line1"></span>
            <span class="line line2"></span>
            <span class="line line3"></span>
        </div>

        <?php
            if(!isset($_SESSION['id'])) {

        ?>
            <ul class="menu-items">
                <li><a href="/check-your-mood?controller=home&action=login">Se connecter</a></li>
            </ul>
        <?php } else { ?>
            <ul class="menu-items">
                <li><a href="/check-your-mood?controller=home&action=index">Accueil</a></li>
                <li><a href="/check-your-mood?controller=donnees&action=changementPage">Humeur</a></li>
                <li><a href="/check-your-mood?controller=donnees&action=goToMood&namepage=visualisation">Visualisation</a></li>
                <li><a href="/check-your-mood?controller=donnees&action=viewModification">Profil</a></li>
                <li><a href="/check-your-mood?controller=deconnexion">Déconnexion</a></li>
            </ul>
        <?php } ?>
    </div>
</nav>