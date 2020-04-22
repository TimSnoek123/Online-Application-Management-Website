@extends('layouts.app')

@section('content')
<div id="login">
    
    <h3 class="text-center text-white pt-5">Login form</h3>
    <div class="container">
        <div id="login-row" class="row justify-content-center align-items-center">
            <div id="login-column" class="col-md-6">
                <div id="login-box" class="col-md-12">

                    <form id="login-form" class="form"  method="post">
                    @csrf
                        <h3 class="text-center text-info">Login</h3>
                        <div id="couldntLogInError" style="color: red; text-align:center; display:none">Couldn't log in, did you use the right email and password?</div>

                        <div class="form-group">
                            <label for="username" class="text-info">Email:</label><br>
                            <input type="text" name="email" id="username" class="form-control">
                            <div id="emailError" style="color:red"></div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="text-info">Password:</label><br>
                            <input type="password" name="password" id="password" class="form-control">
                            <div id="passwordError" style="color:red"></div>
                        </div>
                        <div class="form-group">
                            <label for="remember-me" class="text-info"><span>Remember me</span> <span><input id="remember-me" name="remember-me" type="checkbox"></span></label><br>
                            <input type="submit" name="submit" class="btn btn-info btn-md" value="submit">
                        </div>
                        <div id="register-link" class="text-right">
                            <a href="#" class="text-info">Register here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    
<script>
    function clearAllErrors(){
        $('div[id$="Error"]').each((index, element) => { $(element).hide()})
    }

    $(document).on('submit', 'form', function(e){
        event.preventDefault();
        let formData = $(this).serializeArray();
        let data = {};
        $(formData).each(function(index, obj){
            data[obj.name] = obj.value;
        });
        console.log(data);
        $.ajax({
            type: "POST",
            url: "login",
            contentType: "application/json",
            data: JSON.stringify(data),
            success: function(data){
                    console.log("loged in " + JSON.stringify(data))
                    document.location.href = "/"
            },
            error: function(reject){
                clearAllErrors();
                if (reject.status === 422){
                    var errors = $.parseJSON(reject.responseText);
                    $.each(errors.errors, function(key, val){
                        $('#' + key + "Error").text(val[0]).show();
                    });
                    $('#login-box').height(360)
                }
                else{
                   $('#couldntLogInError').toggle();
                   $('#login-box').height(360)
                }
            }
        })
    })

</script>

@endsection


<style>

#login .container #login-row #login-column #login-box {
  margin-top: 120px;
  max-width: 600px;
  height: 320px;
  border: 1px solid #9C9C9C;
  background-color: whitesmoke;
}
#login .container #login-row #login-column #login-box #login-form {
  padding: 20px;
}
#login .container #login-row #login-column #login-box #login-form #register-link {
  margin-top: -85px;
}
</style>

