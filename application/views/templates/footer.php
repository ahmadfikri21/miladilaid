            </div>
            <footer>
                <div class="container">
                    <div class="boxFooter">
                        <div class="contactUs">
                            <p>Contact us at</p>
                            <p>+62 871234 6666</p>
                        </div>
                        <p class="copyright">Copyright &copy; <?= date("Y") ?> miladila.id</p>
                        <form class="formSubscribe">
                            <label>Subscribe</label><br>
                            <input type="text" class="txtSubSm email" placeholder="Enter your email..."> 
                            <input type="submit" value="Subscribe" class="btnSubSm">
                        </form>
                    </div>
                </div>
            </footer>
            <script src="<?= base_url("assets/js/app.js") ?>"></script>
            <script>
                // agar tampilan angka di cart tidak aneh ketika telah melebihi 9
                var countCart = "<?= $this->cart->total_items() ?>";

                if(countCart > 9){
                    $(".countCart").css("margin-left","-23px");
                }
            </script>
    </body>
</html>