<?php

abstract class TestCase
{
    /**
     * Mocked HTTP Server PID
     */
    protected static $httpServerPID;

    /**
     * Setup Mock Server
     *
     * @param string $ipAddress The IP Address who's server will use.
     * @param integer $port The Port who's server will use.
     * @param string $resourcePath The path to the resource. He needs to reside inside tests/resources folder
     * @return void
     */
    protected static function setupMockServer($ipAddress = null, $port = null, $resourcePath = null)
    {
        if (empty($ipAddress) or empty($port) or empty($resourcePath)) {
            throw new Exception('At setupMockServer function you need to fill all parameters.');
        }

        $currentDir = dirname(__FILE__);

        if (!file_exists($currentDir .'/resources/' . $resourcePath)) {
            throw new Exception('Your $resourcePath needs to exist.');
        }

        //More info based on prefixed exec on the command below, can be found here. http://php.net/manual/pt_BR/function.proc-get-status.php#83628
        $commandMockHTTP = sprintf('exec php -S %1$s:%2$s -t %3$s/resources/%4$s > /tmp/mockHTTPServerLogs 2>&1', $ipAddress, $port, $currentDir, $resourcePath);

        //Extract from here https://stackoverflow.com/a/26110467
        $descriptorspec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w']
        ];
        $pipes = [];
        $proc = proc_open($commandMockHTTP, $descriptorspec, $pipes);
        $proc_details = proc_get_status($proc);
        $pid = $proc_details['pid'];

        self::$httpServerPID = $pid;
    }

	/**
     * Kill Mock Server, using posix_kill function
     *
     * @return void
     */
    protected static function killMockServer()
    {
        if (empty(self::$httpServerPID)) {
            return false;
        }

        @posix_kill(self::$httpServerPID, 9);
    }
}
