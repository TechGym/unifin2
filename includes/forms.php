<?php
function checkTitle($value) {
        global $loggedInfo;
        if (isset($loggedInfo['title']) && trim($value) === trim($loggedInfo['title'])) {
                return "checked";
        }

        return "";
}

function checkData($data) {
        global $loggedInfo;
        if (isset($loggedInfo[$data])) {
                return $loggedInfo[$data];
        }
        return "";
}

function checkDOB() {
        global $loggedInfo;
        if (isset($loggedInfo['dob'])) {
                return $loggedInfo['dob'];
        }
        return "";
}
function formatDOB($date) {
        if ($date !== "") {
                $arr = explode("-", $date);
                $rev = [$arr[1], $arr[2], $arr[0]];
                return join("-", $rev);
        }
}
?>

<article class="editsForm">
        <div class="container">
                <?php if ($_GET["type"] == "name") {  ?>
                        <div class="name">
                                <h3>Edit your personal Information</h3>
                                <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post" id="nameForm">
                                        <label for="firstname">Firstname:</label>
                                        <input type="text" name="firstname" id="firstname" value="<?php echo checkData("firstname") ?>">

                                        <label for="othernames">Other names <span>(optional)</span>:</label>
                                        <input type="text" name="othernames" id="othernames" value="<?php echo checkData("othernames") ?>">

                                        <label for="lastname">Lastname:</label>
                                        <input type="text" name="lastname" id="lastname" value="<?php echo checkData("lastname") ?>">

                                        <p>Select your title</p>
                                        <article class="title">
                                                <div class="mr">
                                                        <input type="radio" name="title" id="mr" value="mr." <?php echo checkTitle("Mr.") ?>>
                                                        <label for=" mr">Mr.</label>
                                                </div>
                                                <div class="mrs">
                                                        <input type="radio" name="title" id="mrs" value="mrs." <?php echo checkTitle("Mrs.") ?>>
                                                        <label for="mrs">Mrs.</label>
                                                </div>
                                                <div class="miss">
                                                        <input type="radio" name="title" id="miss" value="miss" <?php echo checkTitle("Miss.") ?>>
                                                        <label for="miss">Miss.</label>
                                                </div>
                                        </article>
                                        <label for="namePassword">Enter your password to confirm:</label>
                                        <input type="password" name="namePassword" id="namePassword" placeholder="........">

                                        <p class="err"> Please fill in all credentials </p>

                                        <div class="form_controls">
                                                <input type="submit" name="submitNameEdit" value="Save ">
                                                <button class="cancelNameEdit" name="cancelNameEdit" value="cancel">Cancel</button>
                                        </div>
                                </form>
                        </div>
                <?php } elseif ($_GET["type"] == "dob") {  ?>
                        <div class="dob">
                                <h3>Edit your Date Of Birth</h3>
                                <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post" id="dobForm">
                                        <label for="dob">Enter your date of birth:</label>
                                        <input type="text" name="dob" id="dob" placeholder="MM-DD-YYYY" value="<?php echo formatDOB(checkDOB()) ?>">
                                        <label for="password">Enter your password to confirm:</label>
                                        <input type="password" name="password" id="password" placeholder="........">

                                        <p class="err"> Please fill in all credentials </p>

                                        <div class="form_controls">
                                                <input type="submit" name="submitNameEdit" value="Save ">
                                                <button class="cancelNameEdit" name="cancelNameEdit" value="cancel">Cancel</button>
                                        </div>
                                </form>
                        </div>
                <?php } elseif ($_GET["type"] == "email") {  ?>
                        <div class="email">
                                <h3>Edit your email address</h3>
                                <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post" id="emailForm">
                                        <label for="email">Enter new email:</label>
                                        <input type="text" name="email" id="email" placeholder="<?php echo strtolower($loggedInfo['email']) ?>">


                                        <label for="password">Enter your password to confirm:</label>
                                        <input type="password" name="password" id="password" placeholder="........">

                                        <p class="err">Please fill in all credentials</p>
                                        <div class="form_controls">
                                                <input type="submit" value="Save ">
                                                <button class="cancelEmailEdit" name="cancelEmailEdit">Cancel</button>
                                        </div>
                                </form>
                        </div>
                <?php } elseif ($_GET["type"] == "phone") {  ?>
                        <div class="phone">
                                <h3 class="intro">Edit your phone number</h3>
                                <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post" id="phoneForm">
                                        <div class="countries">
                                                <div class="countryName">
                                                        <input type="text" value="<?php echo $loggedInfo["country"] ?>">
                                                </div>
                                                <div class="icon">
                                                        <i class="fa-solid fa-angle-down"></i>
                                                </div>
                                        </div>
                                        <!-- Create a Js field with all available country names  -->
                                        <div class="countriesHolder">

                                        </div>
                                        <label for="number">Enter phone number:</label>
                                        <div class="phoneInfo">
                                                <div class="code">+233</div>
                                                <input type="text" name="number" id="number" value="" placeholder="12345678">
                                        </div>


                                        <label for="password">Enter your password to confirm:</label>
                                        <input type="password" name="password" id="password" placeholder="........">

                                        <p class=" err">Please fill in all credentials</p>
                                        <div class="form_controls">
                                                <input type="submit" value="Save ">
                                                <button class="cancel" name="cancel">Cancel</button>
                                        </div>
                                </form>
                        </div>
                <?php } elseif ($_GET["type"] == "address") {  ?>
                        <div class="address">
                                <h3>Edit your Address Details</h3>
                                <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post" id="addressForm">

                                        <label for="country">Select your country:</label>
                                        <!-- <input type="country" name="country" id="country" value=""> -->
                                        <div class="countries">
                                                <div class="countryName">
                                                        <input type="text" value="<?php echo $loggedInfo["country"] ?>" data-full='<?php echo $loggedInfo["country"] ?>' placeholder="country">
                                                </div>
                                                <div class="icon">
                                                        <i class="fa-solid fa-angle-down"></i>
                                                </div>
                                        </div>
                                        <!-- Create a Js field with all available country names  -->
                                        <div class="countriesHolder">

                                        </div>
                                        <div class="laterAdd">
                                                <label for="state">State:</label>
                                                <input type="text" name="state" id="state" placeholder="<?php echo checkData("state") ?>">

                                                <label for="city">City:</label>
                                                <input type="text" name="city" id="city" placeholder="<?php echo checkData("city") ?>">

                                                <label for="address">Address:</label>
                                                <input type="text" name="address" id="address" placeholder="<?php echo checkData("address") ?>">

                                                <label for="address">Zip Code:</label>
                                                <input type="text" name="zipCode" id="zipCode" placeholder="<?php echo checkData("zipcode") ?>">
                                        </div>

                                        <label for="addressPassword">Enter your password to confirm:</label>
                                        <input type="password" name="addressPassword" id="addressPassword" placeholder="........">

                                        <p class="err"> </p>

                                        <div class="form_controls">
                                                <input type="submit" value="Save ">
                                                <button class="cancelAddressEdit" name="cancelAddressEdit" value="cancel">Cancel</button>
                                        </div>
                                </form>
                        </div>
                <?php } else {
                        header("Location: ./profile");
                } ?>
        </div>
</article>