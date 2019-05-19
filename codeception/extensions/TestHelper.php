<?php
namespace Lemundo\Codeception\Extensions;

use Codeception\Events;
use Codeception\Event\SuiteEvent;
use Symfony\Component\Process\Process;

class TestHelper extends \Codeception\Extension
{
    public static $events = [
        Events::SUITE_BEFORE => 'suiteBefore',
        Events::SUITE_AFTER => 'suiteAfter'
    ];

    /** @var Process */
    private $application;

    public function suiteBefore(SuiteEvent $event)
    {
        $this->writeln('Starting PHP server');
        rename(APP_PATH . '/.env', APP_PATH . '/.env.backup');
        copy(APP_PATH . '/.env.acceptance', APP_PATH . '/.env');
        touch(APP_PATH . '/var/data/acceptance.sqlite');

        $commandLine = [
            'php',
            '-S',
            '0.0.0.0:8080',
            '-t',
            APP_PATH . '/public',
            APP_PATH . '/codeception/router.php'
        ];
        $this->app = new Process($commandLine);
        $this->writeln($this->app->getCommandLine());
        $this->app->start();
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
        if (!$this->app->isRunning()) {
            return;
        }
        $this->app->stop(3);
    }
}