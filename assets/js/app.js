    // fungsi untuk mengambil base url
    function js_base_url(param){
        return "http://localhost/Miladila/"+param;
    }

    // ====== Homepage =======
    $(".formSubscribe").submit(function(e){
        e.preventDefault();

        let email = $(this).children(".email").val();

        if(email != ""){
            $.ajax({
                url : js_base_url("Home/addSubscriber"),
                method : "POST",
                data:{
                    email:email
                },
                success:function(data){
                    $(".email").val("");
                    Swal.fire(
                        "Success",
                        data,
                        "success"
                    );
                }
            });
        }else{
            Swal.fire(
                "Failed",
                "Please, fill out the textfield",
                "error"
            );
        }
    });