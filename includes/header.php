<div class="header_container <?php echo navigationClass() ?>">
    <header>
        <?php
        if (isset($loggedInfo)) { ?>
            <?php $status = fetchStatus($loggedInfo['email']);
            if ($status != "pending") { ?>
                <a href="./profile" class="profile">
                    <i class="fa-solid fa-user"></i>
                </a>
            <?php }  ?>
        <?php }  ?>
        <div class="header__logo">
            <img src="./images/logo.png" alt="">
            <h1>UNIFIN</h1>
        </div>
        <nav class="desktop">
            <a href="./home" class="active home">
                Home
            </a>
            <a href="./swap" class="swap">
                Swap
            </a>
            <?php if (isset($loggedInfo)) {  ?>
                <a href="./profile" class="">
                    Profile
                </a>
            <?php }  ?>
            <a href="./faq" class="faq">
                Faq
            </a>
            <a href="./contact" class="contact">
                Contact
            </a>
        </nav>
        <div class="header__right">
            <?php if (isset($loggedInfo)) {
                if (file_exists('./functions/notifications.func.php')) {
                    include_once "./functions/notifications.func.php";
                } else {
                    include_once "../functions/notifications.func.php";
                }

            ?>
                <div class="notification">
                    <?php if ($unreadNotificationsCount === 0) { ?>
                        <i class="fa-solid fa-bell empty"></i>
                    <?php } else { ?>
                        <i class="fa-solid fa-bell filled "></i>
                    <?php } ?>
                </div>
            <?php } ?>
            <button class="menu__btn phone">
                <i class="fa-solid fa-bars "></i>
            </button>
            <p class="desktop whitepaper">
                <a href="./whitepaper">White paper</a>
            </p>
        </div>
    </header>
</div>