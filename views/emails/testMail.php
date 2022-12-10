<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width">
    <title></title>
</head>
<body>
<div style="text-align:center;color:#343434;font-size:24px;font-family:Calibri,
        sans-serif;font-weight:700;letter-spacing:3px;line-height: 35px;">
    <span>Message From: </span> <span style="color: #5caad2;"><?php /** @var array $data */
        echo $data["site_name"]; ?></span>
</div>
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#fff" style="font-family:Calibri,
sans-serif;">
    <!-- username -->
    <tr style="background: #eee;">
        <td style="width:50%;padding: 5px;">Username:</td>
        <td style="width:50%;padding: 5px;"><?php echo $data["name"]; ?></td>
    </tr>
    <!-- email -->
    <tr>
        <td style="width:50%;padding: 5px;">Email:</td>
        <td style="width:50%;padding: 5px;"><?php echo $data["mail"]; ?></td>
    </tr>
</table>
</body>
</html>
