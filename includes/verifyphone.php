<?php

if ($_GET['type'] == "verifyphone") {
    if (!isset($loggedInfo)) {
        header("Location: ./login");
    }
    if(!isset($_SESSION["phone"])){
        header("Location: ./profile");
    }
 ?>
<!-- Phone validation -->
<section class="verifyDetails">
    <h3>
        Verify your Phone Number.
    </h3>
    <p>
        A verification code has been sent to :
    </p>
    <p class="contact"><?php echo $_SESSION['phone'] ?> </p>
    <p>
        Enter the code below to verify your phone
    </p>
    <form action="" id="verify" class="editPhone">
        <input type="text" placeholder="Enter code here">
        <input type="submit" value="Submit">
    </form>
    <p class="msgError"></p>

</section>
<button class="displayResend">Didn't receive code?</button>
<div class="verify_action" id="verify_action">
    <button class="resend">
        Resend SMS
    </button>
</div>

<?php } ?>