<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!is_file('composer.json')) {
    throw new \RuntimeException('Can\'t find a composer.json file. Make sure to start this script from the project root folder');
}

$rootDir = __DIR__ . '/..';

require_once __DIR__ . '/../app/bootstrap.php.cache';

use Symfony\Component\Console\Output\OutputInterface;

// reset data
$fs = new \Symfony\Component\Filesystem\Filesystem;
$output = new \Symfony\Component\Console\Output\ConsoleOutput();

parse_str($argv[1]);

if(empty($theme)) {
    $theme = 'exim';
}

if(!empty($show) && $show = 'true') {
    $showoutput = true;
} else {
    $showoutput = false;
}

if(!empty($debug) && $debug = 'true') {
    $nodebug = '';
} else {
    $nodebug = ' --no-debug';
}

$output->writeln('');
$output->writeln(sprintf('<info>Theme      : %s</info>', $theme));
for ($i=0; $i < 3; $i++) {
    if(empty($superadmin[$i])) {
        unset($superadmin);
        break;
    }
}
if(empty($superadmin)) {
    $superadmin[] = 'superadmin';
    $superadmin[] = 'superadmin@localhost';
    $superadmin[] = 'superadmin';
}

$output->writeln("");
$output->writeln(sprintf('<info>Super admin: %s</info>', $superadmin[0]));
$output->writeln(sprintf('<info>email      : %s</info>', $superadmin[1]));
$output->writeln(sprintf('<info>password   : %s</info>', $superadmin[2]));

for ($i=0; $i < 3; $i++) {
    if(empty($admin[$i])) {
        unset($admin);
        break;
    }
}

$output->writeln("");
if(empty($admin)) {
    $output->writeln('<info>Admin      : No</info>');
} else {
    $output->writeln(sprintf('<info>Admin      : %s</info>', $admin[0]));
    $output->writeln(sprintf('<info>email      : %s</info>', $admin[1]));
    $output->writeln(sprintf('<info>password   : %s</info>', $admin[2]));
}


// does the parent directory have a parameters.yml file
if (is_file(__DIR__.'/../../parameters.demo.yml')) {
    $fs->copy(__DIR__.'/../../parameters.demo.yml', __DIR__.'/../app/config/parameters.yml', true);
}

if (!is_file(__DIR__.'/../app/config/parameters.yml')) {
    $output->writeln('<error>no default apps/config/parameters.yml file</error>');

    exit(1);
}

/**
 * @param $commands
 * @param \Symfony\Component\Console\Output\ConsoleOutput $output
 *
 * @return boolean
 */
function execute_commands($commands, $output)
{
    foreach($commands as $command) {
        list($command, $message, $allowFailure) = $command;

        $output->write(sprintf(' - %\'.-70s', $message));
        $return = array();
        if (is_callable($command)) {
            $success = $command($output);
        } else {
            $p = new \Symfony\Component\Process\Process($command);
            $p->setTimeout(null);
            $p->run(function($type, $data) use (&$return) {
                $return[] = $data;
            });

            $success = $p->isSuccessful();
        }

        if (!$success && !$allowFailure) {
            $output->writeln('<error>KO</error>');
            $output->writeln(sprintf('<error>Fail to run: %s</error>', is_callable($command) ? '[closure]' : $command));
            foreach($return as $data) {
               $output->write($data, false, OutputInterface::OUTPUT_RAW);
            }

            $output->writeln("If the error is coming from the sandbox,");
            $output->writeln("please report the issue to https://github.com/sonata-project/sandbox/issues");

            return false;
        } else if (!$success) {
            $output->writeln("<info>!!</info>");
        } else {
            $output->writeln("<info>OK</info>");
        }
    }

    return true;
}

$output->writeln(<<<EXIM

                    __   ____   __      __          _
            \    ___) \  \  /  / (_    _) |        |
             |  (__    \  \/  /    |  |   |  |\/|  |
             |   __)    >    <     |  |   |  |  |  |
             |  (___   /  /\  \   _|  |_  |  |  |  |
            /       )_/  /__\  \_(      )_|  |__|  |_
EXIM
);
$output->writeln("");
$output->writeln("<info>Resetting eXim, this can take a few minutes</info>");

$fs->remove(sprintf('%s/web/themes/' . $theme . '/uploads/media', $rootDir));
$fs->mkdir(sprintf('%s/web/themes/' . $theme . '/uploads/media', $rootDir));

// find out the default php runtime
$bin = sprintf("%s -d memory_limit=-1", defined('PHP_BINARY') ? PHP_BINARY: 'php');


if (extension_loaded('xdebug')) {
    $output->writeln("<error>WARNING, xdebug is enabled in the cli, this can drastically slowing down all PHP scripts</error>");
}

$commandsToExecute = array(
    array($bin . ' ./bin/sonata-check.php','Checking Sonata Project\'s requirements', $showoutput),
    array(function(OutputInterface $output) use ($fs) {
        $fs->remove("app/cache/prod");
        $fs->remove("app/cache/dev");

        return true;
    }, 'Deleting prod and dev cache folders', $showoutput),
    array(function(OutputInterface $output) use ($fs) {
        return $fs->exists("app/config/parameters.yml");
    }, 'Check for app/config/parameters.yml file', $showoutput),
    array($bin . ' ./app/console cache:create-cache-class --env=prod' . $nodebug,'Creating the class cache', $showoutput),
    array($bin . ' ./app/console doctrine:database:drop --force','Dropping the database', true),
    array($bin . ' ./app/console doctrine:database:create','Creating the database', $showoutput),
    array($bin . ' ./app/console doctrine:schema:update --force','Creating the database\'s schema', $showoutput),
    array($bin . '  -d max_execution_time=600 ./app/console doctrine:fixtures:load --verbose --env=dev' . $nodebug . ' --no-interaction','Loading fixtures', $showoutput),
    array($bin . ' ./app/console sonata:news:sync-comments-count','Sonata - News: updating comments count', $showoutput),
    array($bin . ' ./app/console sonata:page:update-core-routes --site=all' . $nodebug,'Sonata - Page: updating core route', $showoutput),
    array($bin . ' ./app/console sonata:page:create-snapshots --site=all' . $nodebug,'Sonata - Page: creating snapshots from pages', $showoutput),
    array($bin . ' ./app/console assets:install --symlink web','Configure assets', $showoutput),
    array($bin . ' ./app/console sonata:admin:setup-acl','Security: setting up ACL', $showoutput),
    array($bin . ' ./app/console sonata:admin:generate-object-acl' . $nodebug,'Security: generating object ACL', $showoutput),
);

if(!empty($superadmin)) {
    $commandsToExecute[] = array($bin . ' ./app/console fos:user:create ' . $superadmin[0] . ' ' . $superadmin[1] . ' ' . $superadmin[2],'User: generating super admin', $showoutput);
    $commandsToExecute[] = array($bin . ' ./app/console fos:user:promote ' . $superadmin[0] . ' ROLE_SUPER_ADMIN','User: promote super admin', $showoutput);
}
if(!empty($admin)) {
    $commandsToExecute[] = array($bin . ' ./app/console fos:user:create ' . $admin[0] . ' ' . $admin[1] . ' ' . $admin[2],'User: generating super admin', $showoutput);
    $commandsToExecute[] = array($bin . ' ./app/console fos:user:promote ' . $admin[0] . ' ROLE_ADMIN','User: promote super admin', $showoutput);
}
// $commandsToExecute[] = array($bin . ' ./app/console cache:clear','Clear cache env dev', false),
// $commandsToExecute[] = array($bin . ' ./app/console cache:clear --env=prod','Clear cache env prod', false),

$success = execute_commands($commandsToExecute, $output);

if (!$success) {
    $output->writeln('<info>An error occurs when running a command!</info>');

    exit(1);
}

$output->writeln('');
$output->writeln('<info>What\'s next ?!</info>');
$output->writeln(sprintf(' - Configure your webserver to point to the %s/web folder.', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..')));
$output->writeln(' - Review the documentation:');
$output->writeln('   - https://sonata-project.org/bundles');
$output->writeln(' - Follow us on twitter:');
$output->writeln('   - https://twitter.com/sonataproject');
$output->writeln('   - https://twitter.com/davaskwebltd');

exit(0);
