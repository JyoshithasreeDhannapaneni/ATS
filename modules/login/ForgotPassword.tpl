<?php /* $Id: ForgotPassword.tpl 1947 2007-02-23 09:45:29Z will $ */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Neutara ATS Tool - Login</title>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo(HTML_ENCODING); ?>">
        <?php $faviconSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><rect width="32" height="32" rx="8" fill="#0c18d4"/><text x="16" y="22" font-family="Arial, sans-serif" font-size="18" font-weight="800" fill="#fff" text-anchor="middle">N</text></svg>'; ?>
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<?php echo(rawurlencode($faviconSvg)); ?>" />
        <style type="text/css" media="all">@import "modules/login/login.css";</style>
        <script type="text/javascript" src="js/lib.js"></script>
        <script type="text/javascript" src="modules/login/validator.js"></script>
    </head>

    <body>

    <div id="headerBlock">
        <span id="mainLogo">Neutara ATS</span>
    </div>

    <div id="contents">
        <div id="login">
            <?php if (!empty($this->message)): ?>
                <div>
                    <?php if ($this->messageSuccess): ?>
                        <p class="success"><?php $this->_($this->message); ?><br /></p>
                    <?php else: ?>
                        <p class="failure"><?php $this->_($this->message); ?><br /></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div id="formBlock">
                <img src="images/login.gif" alt="Login" />
                <?php if (!$this->complete): ?>
                    <form name="loginForm" id="loginForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=login&amp;a=forgotPassword" method="post" autocomplete="off">
                        <input type="hidden" name="postback" value="true" />
                        <div id="subFormBlock">
                            <label id="usernameLabel" for="username">Username</label><br />
                            <input name="username" id="username" class="login-input-box" />
                            <br />

                            <input type="submit" id="submit" name="submit" class="login-submit-button" value="Email my Password" />
                        </div>
                    </form>
                <?php else: ?>
                    <p>
                        An email has been sent to <?php $this->_($this->username); ?> containing your password.<br /><br />
                        <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=login">Return to login page</a>
                    </p>
                <?php endif; ?>
            </div>

            <div id="loginText">
                <div class="ctr"><img src="images/security.gif" width="64" height="64" alt="security" /></div>
                <p>Welcome to Neutara ATS!</p>
            </div>

            <div style="clear: both;"></div>
        </div>

        <script type="text/javascript">
            document.loginForm.username.focus();
        </script>

        <br />
        <div id="footerBlock">
            <span class="footerCopyright"><?php echo(COPYRIGHT_HTML); ?></span>
        </div>
    </div>
    </body>
</html>
