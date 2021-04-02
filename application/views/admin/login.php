<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url("assets/css/style.css") ?>">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Login</title>
</head>
<body>
    <?php
        if($this->session->flashdata("succNotice")){
            ?>
            <script>
                Swal.fire(
                    "Success",
                    "<?= $this->session->flashdata("succNotice") ?>",
                    "success"
                );
            </script>
            <?php
            $this->session->unset_userdata("succNotice");
        }elseif($this->session->flashdata("errNotice")){
            ?>
            <script>
                Swal.fire(
                    "Error",
                    "<?= $this->session->flashdata("errNotice") ?>",
                    "error"
                );
            </script>
            <?php
            $this->session->unset_userdata("errNotice");
        }
    ?>
    <div class="loginBody">
        <div class="loginFrame">
            <h1>Welcome Back Admin</h1>
            <div class="loginBox">
                <form action="<?= base_url("Admin/login") ?>" method="POST" autocomplete="off">
                    <div class="formElement">
                        <label>Username</label>
                        <input type="text" name="username" value="<?= set_value("username") ?>" class="<?= fieldErrorCheck("username") ?>" placeholder="Username...">
                        <?= form_error("username") ?>
                    </div>
                    <div class="formElement">
                        <label>Password</label>
                        <div class="inputIcon">
                            <input type="password" id="pass" name="password" value="<?= set_value("password") ?>" class="<?= fieldErrorCheck("password") ?>" placeholder="Password...">
                            <img id="test" src="<?= base_url("assets/img/showIcon.svg") ?>">
                        </div>
                        <?= form_error("password") ?>
                    </div>
                    <div class="btnLoginBox">
                        <a href="<?= base_url() ?>">Back</a>
                        <input type="submit" value="Login">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="<?= base_url("assets/js/app.js") ?>"></script>
    <script>
        $("#test").click(function(){
            var id = $("#pass");
            var img = $("#test");

            if(id.attr("type") == "password"){
                id.attr("type","text");
                img.attr("src",js_base_url("assets/img/hideIcon.svg"));
                img.css("width","20px");
            }else{
                id.attr("type","password");
                img.attr("src",js_base_url("assets/img/showIcon.svg"));
            }
        });
    </script>
</body>
</html>