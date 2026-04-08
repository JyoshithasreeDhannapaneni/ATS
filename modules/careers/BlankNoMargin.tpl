<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo(HTML_ENCODING); ?>">
        <title>Neutara ATS Tool - Job Order - <?php if (isset($this->jobOrderData['title'])) { $this->_($this->jobOrderData['title']); } else { echo 'Careers'; } ?></title>
        <?php global $careerPage; if (isset($careerPage) && $careerPage == true): ?>
            <script type="text/javascript" src="../js/lib.js"></script>
            <script type="text/javascript" src="../js/sorttable.js"></script>
            <script type="text/javascript" src="../js/calendarDateInput.js"></script>
        <?php else: ?>
            <script type="text/javascript" src="js/lib.js"></script>
            <script type="text/javascript" src="js/sorttable.js"></script>
            <script type="text/javascript" src="js/calendarDateInput.js"></script>
        <?php endif; ?>
        <style type="text/css" media="all">
            <?php echo($this->template['CSS']); ?>
        </style>
    </head>
    <body marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" >
    <!-- TOP -->
    <?php echo($this->template['Header']); ?>

    <!-- CONTENT -->
    <?php echo($this->template['Content']); ?>

    <!-- FOOTER -->
    <?php echo($this->template['Footer']); ?>
    <div style="font-size:9px;">
        <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
    </div>
    <div style="text-align:center;">

        <a style="color: #2563eb; position: relative; font-size: 10px; font-weight: 500; text-align: center; left: 0px; top: 0px;" href="https://neutara.com/careers" target="_blank">Careers - Neutara</a>

    </div>
    <script type="text/javascript">st_init();</script>
    </body>
</html>
