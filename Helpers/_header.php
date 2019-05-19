<?php
    echo '<div class="row justify-content-between p-2 bg-primary text-white align-items-center">
                  <a href="./main.php">
                <div class="col-4 text-right font-weight-bold display-4 text-white">
                    Facebook
                </div>
                  </a>
                <div class="col-4">
                    <form action="searchPeople.php" method="POST">
                        <div class="row">                            
                            <div class="col">
                                <input type="text" class="form-control" name="fullname" placeholder="Find People">
                            </div>                    
                            <div class="col">
                                <input type="submit" name="btnSearch" value="Search" class="btn btn-info">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-4 h-100">
                    <a href="profile.php">
                        <div class="btn btn-info"><img class="miniphoto" src="'. $_SESSION["user"]["profile_photo"] .'"> '. $_SESSION["user"]["name"] . " " . $_SESSION["user"]["surname"] .'</div>
                    </a>
                    <a href="friends.php">
                        <div class="btn btn-info p-2">Friends</div>
                    </a>
                    <a href="notification.php">
                        <div class="btn btn-info p-2">Notifications</div>
                    </a>
                    <a href="./Helpers/_logout.php">
                        <div class="btn btn-info p-2">Logout</div>
                    </a>
                </div>
            </div>';
