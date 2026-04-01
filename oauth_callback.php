<?php
/**
 * Microsoft OAuth Callback - Simplified
 */
include_once('./config.php');
include_once('./constants.php');

@session_name(CATS_SESSION_NAME);
session_start();

// Check for id_token in fragment - need JavaScript to pass it
?>
<!DOCTYPE html>
<html>
<head><title>Signing in...</title></head>
<body>
<div id="status">Processing login...</div>
<script>
(function() {
    var params = {};
    
    // Check hash fragment first (for id_token)
    if (window.location.hash) {
        var hash = window.location.hash.substring(1);
        hash.split('&').forEach(function(part) {
            var item = part.split('=');
            params[item[0]] = decodeURIComponent(item[1]);
        });
    }
    
    // Check query string (for errors or code)
    if (window.location.search) {
        var query = window.location.search.substring(1);
        query.split('&').forEach(function(part) {
            var item = part.split('=');
            params[item[0]] = decodeURIComponent(item[1]);
        });
    }
    
    // Handle error
    if (params.error) {
        window.location.href = 'index.php?m=login&message=' + encodeURIComponent(params.error_description || params.error);
        return;
    }
    
    // Handle id_token
    if (params.id_token) {
        // Create form and POST the token
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'oauth_process.php';
        
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id_token';
        input.value = params.id_token;
        form.appendChild(input);
        
        document.body.appendChild(form);
        form.submit();
        return;
    }
    
    // Handle authorization code
    if (params.code) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'oauth_process.php';
        
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'code';
        input.value = params.code;
        form.appendChild(input);
        
        document.body.appendChild(form);
        form.submit();
        return;
    }
    
    // Nothing found
    document.getElementById('status').innerHTML = 'No login data received. <a href="index.php?m=login">Try again</a>';
})();
</script>
</body>
</html>
