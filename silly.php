<?php
declare(strict_types=1);
require 'vendor/autoload.php';

use Silly\Application;
use Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Style\SymfonyStyle;
use App\Pheanstalkd;

$app = new Application('task-system', '0.0.1');

$app->command('help', function (SymfonyStyle $io) {
    $io->listing([
        'help',
        'stats',
        'listTubes',
        'clear',
        'clearAll',
        'statsTube',
        'listTubesWatched',
        'listTubeUsed',
        'pauseTube',
        'resumeTube',
    ]);
});

$app->command('stats', function (SymfonyStyle $io) {
    $pheanstalkd = Pheanstalkd::getInstance();
    $stats = $pheanstalkd->stats();

    $headers = [
        'field',
        'value',
    ];
    $rows = [
        [
            'id',
            $stats['id']
        ],
        [
            'hostname',
            $stats['hostname']
        ],
        [
            'pid',
            $stats['pid']
        ],
        [
            'version',
            $stats['version']
        ],
        [
            'uptime',
            $stats['uptime']
        ],
        [
            'current-jobs-urgent',
            $stats['current-jobs-urgent']
        ],
        [
            'current-jobs-ready',
            $stats['current-jobs-ready']
        ],
        [
            'current-jobs-reserved',
            $stats['current-jobs-reserved']
        ],
        [
            'current-jobs-delayed',
            $stats['current-jobs-delayed']
        ],
        [
            'current-jobs-buried',
            $stats['current-jobs-buried']
        ],
        [
            'total-jobs',
            $stats['total-jobs']
        ],
        [
            'max-job-size',
            $stats['max-job-size']
        ],
        [
            'current-tubes',
            $stats['current-tubes']
        ],
        [
            'current-connections',
            $stats['current-connections']
        ],
        [
            'current-producers',
            $stats['current-producers']
        ],
        [
            'current-workers',
            $stats['current-workers']
        ],
        [
            'current-waiting',
            $stats['current-waiting']
        ],
    ];
    $io->table($headers, $rows);
});

$app->command('listTubes', function (SymfonyStyle $io) {
    $pheanstalkd = Pheanstalkd::getInstance();
    $tubs = $pheanstalkd->listTubes();
    foreach ($tubs as $key => $value) {
        $io->writeln("ID:" . $key . "    " . $value);
    }
});

$app->command('clear tube', function ($tube, SymfonyStyle $io) {

    $pheanstalkd = Pheanstalkd::getInstance();
    try {
        while (1) {
            $job = $pheanstalkd->watch($tube)
                ->reserveWithTimeout(1);

            if (NULL === $job) {
                break;
            }

            $pheanstalkd->delete($job);

        }
        $io->writeln($tube . '\'s jobs was Cleaned up~~~');

    } catch (\Pheanstalk\Exception\DeadlineSoonException $exception) {
        $io->writeln($exception->getMessage());
    }

});

$app->command('clearAll', function (SymfonyStyle $io) {

    $pheanstalkd = Pheanstalkd::getInstance();

    $tubes = $pheanstalkd->listTubes();
    $len = count($tubes);
    $io->createProgressBar($len);
    $io->progressStart($len);

    $that = $this;
    foreach ($tubes as $tube) {
        $that->runCommand('clear ' . $tube);
        $io->progressAdvance();
        $io->writeln($tube . '\'s jobs was Cleaned up~~~');
    }
    $io->progressFinish();

});

$app->command('statsTube tube', function ($tube, SymfonyStyle $io) {
    $pheanstalkd = Pheanstalkd::getInstance();

    $stats = $pheanstalkd->statsTube($tube);

    foreach ($stats as $key => $value) {
        $io->writeln($key . ': ' . $value);
    }

});


$app->command('greet name [--yell]', function ($name, $yell, OutputInterface $output) {
    if ($name) {
        $text = 'Hello, ' . $name;
    } else {
        $text = 'Hello';
    }

    if ($yell) {
        $text = strtoupper($text);
    }

    $output->writeln($text);
})->descriptions('Greet someone', [
    'name' => 'Who do you want to greet?',
    '--yell' => 'If set, the task will yell in uppercase letters',
]);

//$app->command('say', function (SymfonyStyle $io) {
//    $io->progressStart();
//    $io->createProgressBar(1);
//    $io->createProgressBar(2);
//    $io->createProgressBar(3);
//    $io->writeln('hello');
//    $io->progressFinish();
//    $io->warning(123123);
//    $io->note(2222);
//    $io->comment(1111);
//    $io->success(1111);
//    $io->error(1111);
//    $io->listing([
//        1, 2, 3, 4, 5
//    ]);
//    $io->title(2222);
//    $io->block(123123);
//});

$app->command(
    'local [-f|--function=] [-d|--data=] [-p|--path=] [--raw]',
    function (string $function, ?string $data, bool $raw, SymfonyStyle $io) {
    })
    ->defaults([
        'function' => 'main',
    ])
    ->descriptions('**DEPRECATED** Invoke the lambda locally. To pass data to the lambda use the `--data` argument.', [
        '--function' => 'The name of the function in your service that you want to invoke. In Bref, by default, there is only the `main` function.',
        '--data' => 'String data to be passed as an event to your function. You can provide JSON data here.',
        '--raw' => 'Pass data as a raw string even if it is JSON. If not set, JSON data are parsed and passed as an object.',
    ]);

$app->run();