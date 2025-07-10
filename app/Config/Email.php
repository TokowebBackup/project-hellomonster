<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = 'admin@hellomonster.tokoweb.live';
    public string $fromName   = 'Hellomonster Membership';
    public string $recipients = '';

    public string $userAgent = 'CodeIgniter';
    public string $protocol = 'smtp';

    public string $SMTPHost = 'hellomonster.tokoweb.live';
    public string $SMTPUser = 'admin@hellomonster.tokoweb.live';
    public string $SMTPPass = 'Bismillah@321';
    public int    $SMTPPort = 465;
    public int    $SMTPTimeout = 30;
    public bool   $SMTPKeepAlive = false;
    public string $SMTPCrypto = 'ssl'; // pake 'ssl' karena kamu pakai port 465

    public string $mailType = 'html';
    public string $charset = 'UTF-8';
    public bool   $validate = true;
    public int    $priority = 3;

    public string $CRLF = "\r\n";
    public string $newline = "\r\n";

    public bool   $wordWrap = true;
    public int    $wrapChars = 76;

    public bool   $BCCBatchMode = false;
    public int    $BCCBatchSize = 200;

    public bool   $DSN = false;
}
