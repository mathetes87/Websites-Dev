<?php
$cpid = posix_getpid();
exec("ps aux | grep -v grep | grep apache", $psOutput);
if (count($psOutput) > 0)
{
    foreach ($psOutput as $ps)
    {
        $ps = preg_split('/ +/', $ps);
        $pid = $ps[1];

        if($pid != $cpid)
        {
          $result = posix_kill($pid, 9); 
        }
    }
}
?>