<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>领取优惠码</title>


        <!-- Styles -->
        <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
        {{--<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>--}}
        <script src="https://cdn.bootcss.com/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.bootcss.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container">
            <!-- Content here -->
            <div class="row mt-3 mb-3">
                <div class="col-sm-4 offset-sm-4">
                    <div class="card">
                        <div class="card-header">
                            优惠码领取规则
                        </div>
                        <div class="card-body">
                            <p class="card-text">每个员工编号仅可领取一次</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 offset-md-4">
                    <form method="post" action="{{ url('code') }}" id="frm">
                        <div class="form-group">
                            <label for="staff_id">员工编号</label>
                            <input type="text" class="form-control" id="staff_id" placeholder="请输入员工编号">
                            <small class="form-text text-muted">只有输入正确的员工编号才能领哟！</small>
                        </div>

                        <button type="button" id="sub" class="btn btn-primary">提交</button>
                    </form>
                </div>
            </div>

            <div class="row mt-3 mb-3" style="display: none" id="info">
                <div class="col-sm-4 offset-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">员工编号：<span id="staff_text"></span></p>
                            <p class="card-text">优惠码：<span id="code_text"></span></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <script type="text/javascript">
            $(function () {
                var info = $("#info");
                $("#sub").click(function () {
                    $.post($("#frm").attr("action"),{number:$("#staff_id").val()},function (json) {
                        if (json.code == 100000){
                            $("#staff_text").html(json.data.number);
                            $("#code_text").html(json.data.code);
                            info.toggle();
                        }else {
                            alert(json.msg);
                        }
                    },'json');
                });
            });
        </script>
    </body>
</html>
