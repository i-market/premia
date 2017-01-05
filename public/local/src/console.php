<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use CBackup;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DbDump extends Command {
    protected function configure() {
        $this
            ->setName('db:dump')
            ->addArgument('path', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $pathArg = $input->getArgument('path');
        $pathDir = realpath(dirname($pathArg));
        if ($pathDir !== false) {
            // https://github.com/cjp2600/bim-core/blob/bf4f8c216d76c0ca4b5c638d01560fcfb64c83dc/src/Export/Session.php#L156
            require __DIR__.'/dump.php';
            if (!defined('START_EXEC_TIME')) {
                define('START_EXEC_TIME', microtime(true));
            }
            define('NO_TIME', true);
            IntOption('dump_base_skip_stat', 1);
            IntOption('dump_base_skip_search', 1);
            IntOption('dump_base_skip_log', 1);
            $path = $pathDir.'/'.basename($pathArg);
            $state = false;
            CBackup::MakeDump($path, $state);
            $output->writeln($path);
        } else {
            $output->writeln("directory doesn't exist");
        }
    }
}
