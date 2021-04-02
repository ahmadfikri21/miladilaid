        </div>
        <script src="<?= base_url("assets/js/app.js") ?>"></script>
        <script>
            var modal = $("#modal");
            var trigger = $("#modalTrigger");
            var close = $(".close");

            trigger.click(function(){
                modal.css("display","block");
            });

            close.click(function(){
                modal.css("display","none");
            });

            // modal akan menutup jika cursor klik diluar modal
            $(window).click(function(e){
                if(e.target.id == modal.attr("id")){
                    modal.css("display","none");
                }
            });

            $("#openSidebar").click(function(){
                $(".sideNav").css("width","212px");
                $(".adminContent").css("margin-left","212px");
                $(".openSidebar").css("display","none");
            });
            
            $(".closeSidebar").click(function(){
                $(".sideNav").css("width","0px")
                $(".adminContent").css("margin-left","0px")
                $(".openSidebar").css("display","block");
            });
        </script>
    </body>
</html>
    