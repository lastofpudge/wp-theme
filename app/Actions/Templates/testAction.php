<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   <meta name="viewport" content="width=device-width">
   <title></title>
</head>
<body>
    <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" style="font-family:Calibri,sans-serif;">
        <tr>
            <td align="center" style="color:#343434;font-size:24px;font-family:Calibri,sans-serif;font-weight:700;letter-spacing:3px;line-height: 35px;">
                <div>Message From: <span style="color: #5caad2;"><?php echo $site_name; ?></span></div>
            </td>
        </tr>
        <!-- username -->
        <tr style="background: #eee;">
            <td>Username: </td>
            <td><?php echo $name; ?></td>
        </tr>
        <!-- email -->
        <tr>
            <td>Email:</td>
            <td><?php echo $mail; ?></td>
        </tr>
        <!-- phone -->
        <tr style="background: #eee;">
            <td>Phone:</td>
            <td><?php echo $phone; ?></td>
        </tr>
        <!-- message -->
        <tr>
            <td>Message:</td>
            <td><?php echo $message; ?></td>
        </tr>
    </table>
</body>
</html>
