<?php

namespace Threads;


class Query extends \Threaded
{
    protected $sql;
    protected $result;
    public function __construct($sql) {
        $this->sql = $sql;

    }

    public function run()    {
        require '././vendor/swiftmailer/swiftmailer/lib/swift_required.php';
        $mysql = $this->worker->createConnection();
        $sth = $mysql->prepare($this->sql);
        $sth->execute();
        $results = $sth->fetchAll();
        $transport = new \Swift_SmtpTransport();
        $transport->setHost('node4.mailpixels.com');
        $transport->setPort(587);
        $transport->setPassword(spiceuser);
        $transport->setUsername('dinu.865@gmail.com');
        $transport->setPassword('spiceuser');
        $transport->setEncryption('TLS');


        $mailer = new \Swift_Mailer($transport);
        $message = new \Swift_Message('PHP TEST PThreads');
            ->setFrom(array('no-reply@freshworker.com' => 'PHP Test'))
            ->setBody('PHP Threads Test');
        foreach ($results as $result){
            $message->setTo($result['email']);
            $mailer->send($message);
        }
    }
}