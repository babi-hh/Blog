<div style="background: rgb(255, 255, 255); margin: -100px auto auto; border: 1px solid rgb(231, 231, 231); border-image: none; width: 400px; height: 200px; text-align: center;">
    <div style="width: 165px; height: 96px; position: absolute;">
        <div class="tou"></div>
        <div class="initial_left_hand" id="left_hand"></div>
        <div class="initial_right_hand" id="right_hand"></div>
    </div>
    <form action="" method="post">
        <P style="padding: 30px 0px 10px; position: relative;">
            <label class="u_logo" for="username"></label> 
            <INPUT class="ipt" id="username" type="text" placeholder="请输入用户名或邮箱" value="">
        </P>
        <P style="position: relative;">
            <label class="p_logo" for="password"></label> 
            <INPUT class="ipt" id="password" type="password" placeholder="请输入密码" value="">
        </P>
        <div style="height: 50px; line-height: 50px; margin-top: 30px; border-top-color: rgb(231, 231, 231); border-top-width: 1px; border-top-style: solid;">
            <P style="margin: 0px 35px 20px 45px;">
                <SPAN style="float: left;">
                    <A style="color: rgb(204, 204, 204);"href="#">忘记密码?</A>
                </SPAN> 
                <SPAN style="float: right;">
                    <A style="color: rgb(204, 204, 204); margin-right: 10px;" href="<?= $this->url('register') ?>">注册</A>  
                    <input class=''style="background: rgb(0, 142, 173); padding: 7px 10px; border-radius: 4px; border: 1px solid rgb(26, 117, 152); border-image: none; color: rgb(255, 255, 255); font-weight: bold;cursor: pointer" type="submit" name="submit" value="提交">
                </SPAN> 
            </P>
        </div>
    </form>
    <script type="text/javascript">
        $(function () {
            //得到焦点
            $("#password").focus(function () {
                $("#left_hand").animate({
                    left: "150",
                    top: " -38"
                }, {step: function () {
                        if (parseInt($("#left_hand").css("left")) > 140) {
                            $("#left_hand").attr("class", "left_hand");
                        }
                    }}, 2000);
                $("#right_hand").animate({
                    right: "-64",
                    top: "-38px"
                }, {step: function () {
                        if (parseInt($("#right_hand").css("right")) > -70) {
                            $("#right_hand").attr("class", "right_hand");
                        }
                    }}, 2000);
            });
            //失去焦点
            $("#password").blur(function () {
                $("#left_hand").attr("class", "initial_left_hand");
                $("#left_hand").attr("style", "left:100px;top:-12px;");
                $("#right_hand").attr("class", "initial_right_hand");
                $("#right_hand").attr("style", "right:-112px;top:-12px");
            });
        });
    </script>
</div>