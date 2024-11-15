<html>    
<head>         
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">        
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>        
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>    
</head>
<body>    
    <form class="form-horizontal" action="process_new_user.php" method="get">       
        <fieldset>        
            <legend>Register New Account</legend>        

            <!-- Username field -->
            <div class="form-group">            
                <label class="col-md-4 control-label" for="username">New Username</label>            
                <div class="col-md-5">                
                    <input id="username" type="text" name="username" placeholder="Your name" class="form-control input-md" required>                
                    <p class="help-block">Create a new login name</p>            
                </div>         
            </div>        

            <!-- Password field -->
            <div class="form-group">            
                <label class="col-md-4 control-label" for="password">New Password</label>            
                <div class="col-md-5">                
                    <input id="password" type="password" name="password" placeholder="" class="form-control input-md" required>                
                    <p class="help-block">Create a new password</p>            
                </div>        
            </div>         

            <!-- Confirm Password field -->
            <div class="form-group">            
                <label class="col-md-4 control-label" for="password-confirm">Confirm Password</label>            
                <div class="col-md-5">                
                    <input id="password-confirm" type="password" name="password-confirm" placeholder="" class="form-control input-md" required>                
                    <p class="help-block">Retype the password</p>
                </div>        
            </div>        

            <!-- Submit button -->
            <div class="form-group">            
                <label class="col-md-4 control-label" for="submit"></label>            
                <div class="col-md-4">            
                    <button id="submit" name="submit" class="btn btn-primary">OK</button>            
                </div>        
            </div>    
        </fieldset>
    </form>
</body>
</html>
