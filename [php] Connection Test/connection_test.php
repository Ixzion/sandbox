<?php

  $timeout = 5;
  $host = (isset($_REQUEST['host']) ? $_REQUEST['host'] : $_SERVER['REMOTE_ADDR']);
  $port = (isset($_REQUEST['port']) ? $_REQUEST['port'] : 80);

  $safe_host = htmlentities($host);
  $safe_port = htmlentities($port);

  $host_ci = strtolower($host);
  
  if (isset($_REQUEST['host']) && isset($_REQUEST['port']))
  {
    if (strlen($host) == 0 || $port <= 0 || !is_numeric($port))
    {
      $resulttype = 'bad';
      $resultinfo = "You did not provide valid information.\n\nHost must be provided and port must be greater than zero.";
    }
    else if (strpos($host_ci, '://') !== false)
    {
      $resulttype = 'bad';
      $resultinfo = "You did not provide valid information.\n\nOther socket types are strictly not allowed. You can use TCP only.";
    }
    else if ($host_ci == 'localhost' || $host_ci == 'localhost.localdomain' || $host_ci == 'fedorabox'
      || substr($host_ci, 0, 4) == '127.' || substr($host_ci, 0, 8) == '192.168.' || substr($host_ci, 0, 7) == '172.16.' || substr($host_ci, 0, 3) == '10.')
    {
      $resulttype = 'bad';
      $resultinfo = "You did not provide valid information.\n\nHost must not refer to a local address.";
    }
    else
    {
      $result = @fsockopen($host, $port, $errno, $error, $timeout);
      if ($errno == 0)
      {
        $resulttype = 'good';
        $resultinfo = 'Connection successful.';
      }
      else
      {
        $resulttype = 'bad';
        $resultinfo = "Connection failure.\n\nError #$errno: $error";
      }
      @fclose($result);
    }
  }
  else
  {
    $resulttype = '';
    $resultinfo = "Use the form above to generate your test connection.\n\nThe test will time-out after $timeout seconds.";
  }

  $safe_resulttype = htmlentities($resulttype);
  $safe_resultinfo = str_replace("\n", "<br />", htmlentities($resultinfo));

?><!DOCTYPE html>
<html>
<head>

<title>TCP Connection Test</title>
<style type="text/css">

* { margin: 0; padding: 0; }
html { background-color: #FFFFFF; color: #000000; font: 12pt Sans-Serif; }
#panel {
  background-color: #F0F0F0;
  border: 1px solid #000000;
  height: 300px;
  margin: 4em auto 4em auto;
  padding: 1em;
  width: 400px;
}
table {
  border-collapse: collapse;
  width: 100%;
}
thead td[colspan="2"] {
  border-bottom: 1px solid #000000;
  font-weight: bold;
}
td[colspan="2"] {
  text-align: center;
}
tbody td {
  padding: 0.1em;
}
input {
  font: 12pt Sans-Serif;
  padding: 0.1em 0.2em 0.1em 0.2em;
}
input[type="text"] {
  background-color: #FAFAFA;
  border: 1px solid #808080;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  width: 100%;
}
#result {
  background-color: #FAFAFA;
  border: 1px solid #808080;
  font-family: Monospace;
  font-size: 10pt;
  margin-top: 1em;
  max-height: 177px;
  max-width: 393px;
  overflow: auto;
  padding: 0.3em;
}
.hidden {
  display: none;
}
/*
.good,.bad {
  white-space: nowrap;
}
*/
.good {
  color: #008000;
}
.bad {
  color: #800000;
}

</style>
</head>
<body>
  <div id="panel">
    <form method="GET" action="./<?php echo basename($_SERVER['SCRIPT_NAME']); ?>">
      <table>
        <thead><tr><td colspan="2">TCP Connection Test</td></tr></thead>
        <tbody>
          <tr><td colspan="2">&nbsp;</td></tr>
          <tr><td><label for="host">Host:</label></td><td><input type="text" name="host" id="host" value="<?php echo $safe_host; ?>" /></td></tr>
          <tr><td><label for="port">Port:</label></td><td><input type="text" name="port" id="port" value="<?php echo $safe_port; ?>" /></td></tr>
          <tr><td colspan="2"><input type="submit" value="Test" /> &nbsp;&nbsp; <input type="reset" value="Reset" /></td></tr>
        </tbody>
      </table>
    </form>
    <div id="result" <?php echo (strlen($safe_resulttype) > 0 ? 'class="'.$safe_resulttype.'"' : ''); ?>><?php echo $safe_resultinfo; ?></div>
  </div>
</body>
</html>