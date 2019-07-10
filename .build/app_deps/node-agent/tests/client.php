#!/usr/local/bin/php
<?php
/**
 * usage: php upload_client.php -h 127.0.0.1 -p 9507 -f test.jpg
 */
require_once dirname(sw_get_magic_dir()) . '/src/_init.php';
$encrypt_key = '';
$client = new NodeAgent\Client($encrypt_key);
$args = getopt("p:h:f:t");

if (empty($args['h']) or empty($args['f'])) 
{
    echo "Usage: php {$argv[0]} -h server_ip -p server_port -f file -t timeout\n";
    exit;
}

if (empty($args['p']))
{
	$args['p'] = 9507;
}

if (empty($args['t'])) 
{
    $args['t'] = 30;
}

$file = $args['f'];
if (!is_file($file))
{
    die("Error: file '{$args['f']}' not found\n");
}

/**
 * 连接到服务器
 */
if (!$client->connect($args['h'], $args['p'], $args['t']))
{
    echo "Error: connect to server failed. " . swoole_strerror($client->errCode);
    die("\n");
}

//$res = $client->request(['cmd' => 'getNodeList']);
//var_dump($res);
//exit;

$remote_file = '/opt/swoole/testnode/' . basename($file);
$client->UploadCallback = function ($send_n, $total)
{
    echo "$send_n/$total\n";
};
if (!$client->upload($file, $remote_file))
{
    die("upload success.\n");
}

//var_dump($client->execute('test.sh'), $client->errCode);
//var_dump($client->delete(['/tmp/test1.txt', '/tmp/test2.txt']));