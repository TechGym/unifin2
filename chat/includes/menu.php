<?php
if (isset($loggedInfo)) {
  $status = fetchStatus($loggedInfo['email']);
  $council = checkUsersCouncil($loggedInfo['email']);
}
?>

<div class="cover__screen phone <?php echo navigationClass() ?>"></div>
<div class="phone__menu <?php echo navigationClass() ?>">
  <div class="phone__menu__top">
    <?php if (isset($loggedInfo)) { ?>
      <div class="userInfo">
        <div class="address">
          <div class="profile">
            <img src="../images/logo.png" alt="">
          </div>
          <p class="">
            <?php echo $loggedInfo['firstname'] ?></p>
          <!-- Verification badge -->
          <?php
          if ($status == "member" || $status == 'pool') {
          ?>
            <div class="badge"></div>
          <?php  }
          ?>
        </div>
        <?php if (isset($currentBalance)) { ?>
          <p class="balance"><?php echo $currentBalance; ?> ePHI</p>
        <?php  } ?>
      </div>
    <?php } ?>
    <button class="phone__menu__close">
      X
    </button>
  </div>
  <nav>
    <ul>
      <a href="../home" class="home">
        <div class="icon">
          <i class="fa-solid fa-house"></i>
        </div>
        <li>Home </li>
      </a>
      <a href="../dashboard" class="dashboard">
        <div class="icon">
          <i class="fa-solid fa-gauge"></i>
        </div>
        <li> Dashboard</li>
      </a>
      <?php if ($council === "5") { ?>

        <a href="./admin" class="admin">
          <div class="icon">
            <i class="fa-solid fa-user"></i>
          </div>
          <li> Admin </li>
        </a>
      <?php } ?>
      <a href="../profile" class="profile">
        <div class="icon">
          <i class="fa-solid fa-user"></i>
        </div>
        <li> Profile</li>
      </a>
      <a href="../swap" class="swap">
        <div class="icon">
          <i class="fa-sharp fa-solid fa-right-left"></i>
        </div>
        <li>Swap </li>
      </a>
      <?php $council =  checkUsersCouncil($loggedInfo['email']);
      if ($council == 5 || $council == 50) { ?>
        <a href="../proposals" class="proposals">
          <div class="icon">
            <i class="fa-solid fa-user"></i>
          </div>
          <li> Proposals</li>
        </a>
      <?php } ?>
      <a href="../referrals" class="referrals">
        <div class="icon">
          <i class="fa-solid fa-users"></i>
        </div>
        <li>Referral Team </li>
      </a>
      <a href="../campaign" class="campaign">
        <div class="icon">
          <i class="fa-solid fa-chart-column"></i>
        </div>
        <li>Campaign </li>
      </a>
      <a href="../chat" class="chat">
        <div class="icon">
          <i class="fa-solid fa-message"></i>
        </div>

        <li>Chat </li>
      </a>
      <a href="../whitepaper" class="whitepaper">
        <div class="icon">
          <i class="fa-solid fa-file"></i>
        </div>
        <li> White Paper</li>
      </a>
      <a href="../faq" class="faq">
        <div class="icon">
          <i class="fa-solid fa-circle-question"></i>
        </div>
        <li> FAQ</li>
      </a>
      <button class="logout">
        <div class="icon">
          <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </div>
        <li> logout</li>
      </button>
    </ul>
  </nav>
</div>