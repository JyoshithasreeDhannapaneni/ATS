<?php /* $Id: Login.tpl 3530 2007-11-09 18:28:10Z brian $ */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Neutara ATS Tool - Login</title>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo(HTML_ENCODING); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
        <style type="text/css" media="all">@import "modules/login/login.css";</style>
        <script type="text/javascript" src="js/lib.js"></script>
        <script type="text/javascript" src="modules/login/validator.js"></script>
        <script type="text/javascript" src="js/submodal/subModal.js"></script>
    </head>

    <body>
    <!-- CATS_LOGIN -->
    <?php TemplateUtility::printPopupContainer(); ?>

        <div id="contents">
            <!-- Animated background particles -->
            <div class="bg-particles">
                <div class="particle particle-1"></div>
                <div class="particle particle-2"></div>
                <div class="particle particle-3"></div>
                <div class="particle particle-4"></div>
                <div class="particle particle-5"></div>
            </div>

            <div id="login" style="margin-top: 0;">
                <div id="loginText">
                    <?php if (ENABLE_DEMO_MODE && !($this->siteName != '' && $this->siteName != 'choose') || ($this->siteName == 'demo')): ?>
                        <a href="javascript:void(0);" onclick="demoLogin(); return false;">Login to Demo Account</a><br />
                    <?php endif; ?>
                </div>

                <!-- Login Form -->
                <div id="formBlock">
                    <div class="login-brand">
                        <img src="images/Neutaralogo.jpg" alt="Neutara ATS Tool" class="brand-logo" />
                        <h1 class="brand-title">Neutara ATS</h1>
                        <p class="brand-subtitle">Sign in to your account</p>
                    </div>
                    <form name="loginForm" id="loginForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=login&amp;a=attemptLogin<?php if ($this->reloginVars != ''): ?>&amp;reloginVars=<?php echo($this->reloginVars); ?><?php endif; ?>" method="post" onsubmit="return checkLoginForm(document.loginForm);" autocomplete="off">
                        <div id="subFormBlock">
                            <?php if ($this->siteName != '' && $this->siteName != 'choose'): ?>
                                <?php if ($this->siteNameFull == 'error'): ?>
                                    <label>This site does not exist. Please check the URL and try again.</label>
                                    <br />
                                    <br />
                                <?php else: ?>
                                    <label><?php $this->_($this->siteNameFull); ?></label>
                                    <br />
                                    <br />
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if ($this->siteNameFull != 'error'): ?>
                                <div class="input-group">
                                    <label id="usernameLabel" for="username">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        Username
                                    </label>
                                    <input name="username" id="username" class="login-input-box" placeholder="Enter your username" value="<?php if (isset($this->username)) $this->_($this->username); ?>" />
                                </div>

                                <div class="input-group">
                                    <label id="passwordLabel" for="password">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                                        Password
                                    </label>
                                    <div class="password-wrapper">
                                        <input type="password" name="password" id="password" class="login-input-box" placeholder="Enter your password" />
                                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility()" id="togglePwdBtn">
                                            <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <input type="submit" class="button" value="Sign In" />
                                <input type="reset" id="reset" name="reset" class="button" value="Reset" />
                                
                                <!-- Microsoft SSO Option -->
                                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                                    <p style="text-align: center; font-size: 12px; color: #9ca3af; margin-bottom: 12px;">Or sign in with</p>
                                    <a href="<?php echo(CATSUtility::getIndexName()); ?>?m=login&amp;a=microsoftSSO" class="microsoft-sso-btn" style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 12px 20px; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 6px; color: #1f2937; font-size: 14px; font-weight: 500; text-decoration: none; cursor: pointer; transition: all 0.2s ease;">
                                        <svg width="18" height="18" viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="1" y="1" width="9" height="9" fill="#f25022"/>
                                            <rect x="11" y="1" width="9" height="9" fill="#7fba00"/>
                                            <rect x="1" y="11" width="9" height="9" fill="#00a4ef"/>
                                            <rect x="11" y="11" width="9" height="9" fill="#ffb900"/>
                                        </svg>
                                        Microsoft
                                    </a>
                                </div>
                            <?php else: ?>
                                <br />
                                <a href="javascript:void(0);" onclick="demoLogin(); return false;">Login to Demo Account</a><br />
                            <?php endif; ?>
                            <br /><br />
                        </div>
                    </form>

                    <span style="line-height: 24px; font-size: 11px; color: #9ca3af; display: block; text-align: center; margin-top: 8px;">Version <?php echo(CATSUtility::getVersion()); ?></span>
                </div>
                <div style="clear: both;"></div>
            </div>
            <br />

            <script type="text/javascript">
                <?php if ($this->siteNameFull != 'error'): ?>
                    function demoLogin()
                    {
                        document.getElementById('username').value = '<?php echo(DEMO_LOGIN); ?>';
                        document.getElementById('password').value = '<?php echo(DEMO_PASSWORD); ?>';
                        document.getElementById('loginForm').submit();
                    }
                    function defaultLogin()
                    {
                        document.getElementById('username').value = 'admin';
                        document.getElementById('password').value = 'EnggOps2026!';
                        document.getElementById('loginForm').submit();
                    }
                <?php endif; ?>
                <?php if (isset($_GET['defaultlogin'])): ?>
                    defaultLogin();
                <?php endif; ?>
            </script>

            <!-- Form interaction logic -->
            <script type="text/javascript">
                function togglePasswordVisibility() {
                    var pwd = document.getElementById('password');
                    var eyeIcon = document.getElementById('eyeIcon');
                    if (pwd.type === 'password') {
                        pwd.type = 'text';
                        eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
                    } else {
                        pwd.type = 'password';
                        eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
                    }
                }

                // Auto-focus username on page load
                document.addEventListener('DOMContentLoaded', function() {
                    var usernameField = document.getElementById('username');
                    if (usernameField) usernameField.focus();
                });
            </script>
            
            <style type="text/css">
                .microsoft-sso-btn:hover {
                    background: #f9fafb !important;
                    border-color: #0078d4 !important;
                    box-shadow: 0 4px 12px rgba(0, 120, 212, 0.15) !important;
                    transform: translateY(-1px);
                }
                .microsoft-sso-btn:active {
                    transform: translateY(0);
                    box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
                }
            </style>

            <div id="loginMessage" style="margin-top: 16px; width: 100%; max-width: 520px;">
                <?php if (!empty($this->message)): ?>
                    <div>
                        <?php if ($this->messageSuccess): ?>
                            <p class="success"><?php $this->_($this->message); ?></p>
                        <?php else: ?>
                            <p class="failure"><?php $this->_($this->message); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div id="footerBlock">
                <span class="footerCopyright"><?php echo(COPYRIGHT_HTML); ?></span><br />
                <a href="https://neutara.com/careers" target="_blank">Careers - Neutara</a>
            </div>
        </div>


        <script type="text/javascript">
            initPopUp();
        </script>
        <?php TemplateUtility::printCookieTester(); ?>
    </body>
</html>
