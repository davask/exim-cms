<?php

/*
 * This file is part of the exim-cms package.
 *
 * (c) David Asquiedge <contact@davaskweblimited.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$load_data = file_get_contents(__DIR__ . '/load_data.php');

$load_data = preg_replace('/^<\?php/i', "", $load_data);
$load_data = preg_replace('/exit\(0\);$/i', "", $load_data);

eval($load_data);

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


$output->writeln(<<<EXIM

                   ____  ___.__
              ____ \   \/  /|__| _____
            _/ __ \ \     / |  |/     \
            \  ___/ /     \ |  |  Y Y  \
             \___  >___/\  \|__|__|_|  /
                 \/      \_/         \/
EXIM
);
$output->writeln("");
$output->writeln("<info>Resetting eXim, this can take a few minutes</info>");

$fs->remove(sprintf('%s/web/uploads/media', $rootDir));
$fs->remove(sprintf('%s/web/themes/' . $theme . '/uploads/media', $rootDir));
$fs->mkdir(sprintf('%s/web/themes/' . $theme . '/uploads/media', $rootDir));

// find out the default php runtime
$bin = sprintf("%s -d memory_limit=-1", defined('PHP_BINARY') ? PHP_BINARY: 'php');


if (extension_loaded('xdebug')) {
    $output->writeln("<error>WARNING, xdebug is enabled in the cli, this can drastically slowing down all PHP scripts</error>");
}

$commandsToExecute = array();

if(!empty($superadmin)) {
    $commandsToExecute[] = array($bin . ' ./app/console fos:user:create ' . $superadmin[0] . ' ' . $superadmin[1] . ' ' . $superadmin[2],'User: generating super admin', $showoutput);
    $commandsToExecute[] = array($bin . ' ./app/console fos:user:promote ' . $superadmin[0] . ' ROLE_SUPER_ADMIN','User: promote super admin', $showoutput);
}
if(!empty($admin)) {
    $commandsToExecute[] = array($bin . ' ./app/console fos:user:create ' . $admin[0] . ' ' . $admin[1] . ' ' . $admin[2],'User: generating super admin', $showoutput);
    $commandsToExecute[] = array($bin . ' ./app/console fos:user:promote ' . $admin[0] . ' ROLE_ADMIN','User: promote super admin', $showoutput);
}

$success = execute_commands($commandsToExecute, $output);

if (!$success) {
    $output->writeln('<info>An error occurs when running a command!</info>');

    exit(1);
}

$output->writeln('');
$output->writeln('<info>What\'s next ?!</info>');
$output->writeln(' - Follow us on twitter: https://twitter.com/davaskwebltd');

exit(0);
