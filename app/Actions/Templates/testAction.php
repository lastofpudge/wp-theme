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
        <thead>
            <div style="text-align:center;color:#343434;font-size:24px;font-family:Calibri,
            sans-serif;font-weight:700;letter-spacing:3px;line-height: 35px;">
                <span>Message From: </span> <span style="color: #5caad2;"><?php echo $site_name; ?></span>
            </div>
        </thead>
        <!-- username -->
        <tr style="background: #eee;">
            <td style="width:50%;padding: 5px;">Username: </td>
            <td style="width:50%;padding: 5px;"><?php echo $name; ?></td>
        </tr>
        <!-- email -->
        <tr>
            <td style="width:50%;padding: 5px;">Email:</td>
            <td style="width:50%;padding: 5px;"><?php echo $mail; ?></td>
        </tr>
        <!-- phone -->
        <tr style="background: #eee;">
            <td style="width:50%;padding: 5px;">Phone:</td>
            <td style="width:50%;padding: 5px;"><?php echo $phone; ?></td>
        </tr>
        <!-- message -->
        <tr>
            <td style="width:50%;padding: 5px;">Message:</td>
            <td style="width:50%;padding: 5px;"><?php echo $message; ?></td>
        </tr>
    </table>
</body>
</html>
