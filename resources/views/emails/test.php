<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
  </head>
  <body style="font-family:Calibri, sans-serif;">
    <h2 style="text-align:center; color:#343434; font-size:24px; font-weight:700; letter-spacing:3px; line-height:35px;">
      Message From: 
      <span style="color: #5caad2;">
        <?php echo $data['site_name']; ?>
      </span>
    </h2>
    <table style="width:100%; border-collapse:collapse; background:#fff;">
      <tr style="background: #eee;">
        <td style="width:50%; padding:5px;">Username:</td>
        <td style="width:50%; padding:5px;">
          <?php echo $data['name']; ?>
        </td>
      </tr>
      <tr>
        <td style="width:50%; padding:5px;">Email:</td>
        <td style="width:50%; padding:5px;">
          <?php echo $data['mail']; ?>
        </td>
      </tr>
    </table>
  </body>
</html>