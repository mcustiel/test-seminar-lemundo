<?php

namespace Lemundo\Codeception\Extensions;

use Codeception\Event\SuiteEvent;
use Codeception\Events;
use Symfony\Component\Process\Process;

class TestHelper extends \Codeception\Extension
{
    public static $events = [
        Events::SUITE_BEFORE => 'suiteBefore',
        Events::SUITE_AFTER  => 'suiteAfter',
    ];

    /** @var Process */
    private $application;

    public function suiteBefore(SuiteEvent $event)
    {
        $this->writeln('Starting PHP server');
        rename(APP_PATH . '/.env', APP_PATH . '/.env.backup');
        copy(APP_PATH . '/.env.acceptance', APP_PATH . '/.env');

        $commandLine = [
            'php',
            '-S',
            '0.0.0.0:8080',
            '-t',
            APP_PATH . '/public',
            APP_PATH . '/codeception/router.php',
        ];
        $this->application = new Process($commandLine);
        $this->writeln($this->application->getCommandLine());
        $this->application->start();
        sleep(1);
    }

    public function suiteAfter()
    {
        $this->writeln('Stopping PHP server');
        if (file_exists(APP_PATH . '/.env.backup')) {
            rename(APP_PATH . '/.env.backup', APP_PATH . '/.env');
        }
        if (file_exists(APP_PATH . '/var/data/acceptance.sqlite')) {
            unlink(APP_PATH . '/var/data/acceptance.sqlite');
        }
        if (!$this->application->isRunning()) {
            return;
        }
        $this->application->stop(3);
    }
}
