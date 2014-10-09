<?php

namespace DedicatedManager\Setup;

use DedicatedManager\Config as DedicatedManagerConfig;
use DedicatedManager\Utils\Validation;
use InvalidArgumentException;
use ManiaLib\Application\Config as ApplicationConfig;
use ManiaLib\Database\Config as DatabaseConfig;
use ManiaLib\Database\Connection;
use ManiaLib\Database\Exception as DatabaseException;
use ManiaLib\WebServices\Config as WSConfig;
use Maniaplanet\WebServices\Exception as WSException;
use Maniaplanet\WebServices\Manialinks;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class SetupCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('setup')
            ->setDescription('Help user to configure Maniaplanet Dedicated Manager');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $successStyle = new OutputFormatterStyle('white', 'green');
        $output->getFormatter()->setStyle('success', $successStyle);

        $output->writeln('<info>Welcome in the Maniaplanet Dedicated Manager configuration tool</info>');
        $output->writeln('');
        $this->configureApplication($input, $output);
        $this->configureManiaConnect($input, $output);
        $this->configureDatabase($input, $output);

        $this->createManagerSchema($output);
        $this->saveConfiguration($input, $output);
    }

    protected function configureApplication(InputInterface $input, OutputInterface $output)
    {
        /* @var $questionHelper QuestionHelper */
        $questionHelper = $this->getHelper('question');

        $applicationConfig      = ApplicationConfig::getInstance();
        $dedicatedManagerConfig = DedicatedManagerConfig::getInstance();

        $urlQuestion = new Question('Please enter the URL to the manager: ');
        $urlQuestion
            ->setMaxAttempts(3)
            ->setValidator(function ($url) {
                try {
                    Validation::url($url);
                } catch (InvalidArgumentException $e) {
                    throw new \RuntimeException('Invalid URL');
                }
                if (stripos($url, 'http') !== 0) {
                    throw new \RuntimeException('Invalid URL');
                }
                return $url;
            });

        $dedicatedPathQuestion = new Question('Please enter the path to the dedicated server: ');
        $dedicatedPathQuestion
            ->setMaxAttempts(3)
            ->setValidator(function ($answer) {
                $path = realpath($answer);
                if (!is_dir($path)) {
                    throw new \RuntimeException('Invalid path to Dedicated Server');
                }
                if (!file_exists($path.'/ManiaPlanetServer')) {
                    throw new \RuntimeException('Invalid path to Dedicated Server');
                }
                return $path;
            });

        $adminQuestion = new Question('Please enter the login of an admin for this site: ');
        $adminQuestion
            ->setMaxAttempts(3)
            ->setValidator(function ($login) {
                if ($login && strlen($login) <= 25) {
                    if (preg_match('/^[a-zA-Z0-9-_\.]{1,25}$/iu', $login)) {
                        return $login;
                    }
                }
                throw new \RuntimeException('Invalid player login');
            });
        $moreAdminQuestion = new ConfirmationQuestion('Add another admin (y or n)? (default: n) ', false);

        $output->writeln('<info>Application configuration</info>');

        $applicationConfig->URL                = $questionHelper->ask($input, $output, $urlQuestion);
        $dedicatedManagerConfig->dedicatedPath = $questionHelper->ask($input, $output,
            $dedicatedPathQuestion);
        do {
            $dedicatedManagerConfig->admins[] = $questionHelper->ask($input, $output, $adminQuestion);
        } while ($questionHelper->ask($input, $output, $moreAdminQuestion));
    }

    protected function configureDatabase(InputInterface $input, OutputInterface $output)
    {
        /* @var $questionHelper QuestionHelper */
        $questionHelper = $this->getHelper('question');
        $maxAttempt     = 3;
        $attemptCount   = 1;
        $isConnected    = false;
        $config         = DatabaseConfig::getInstance();

        $output->writeln('<info>Database configuration</info>');
        $hostQuestion = new Question('Please enter the database host (default: 127.0.0.1): ',
            '127.0.0.1');
        $portQuestion = new Question('Please enter the database port (default: 3306): ', 3306);
        $userQuestion = new Question('Please enter the database username (default: root): ', 'root');
        $passQuestion = new Question('Please enter the database password (default: empty): ', '');
        $passQuestion
            ->setHidden(true)
            ->setHiddenFallback(true);

        do {

            $host             = $questionHelper->ask($input, $output, $hostQuestion);
            $port             = $questionHelper->ask($input, $output, $portQuestion);
            $config->host     = $host.':'.$port;
            $config->user     = $questionHelper->ask($input, $output, $userQuestion);
            $config->password = $questionHelper->ask($input, $output, $passQuestion);

            try {
                Connection::getInstance();
                $isConnected = true;
            } catch (DatabaseException $e) {
                $errorMessage = 'Invalid database parameters';
                if ($attemptCount < $maxAttempt) {
                    $output->writeln(sprintf('<error>%s</error>', $errorMessage));
                } else {
                    throw new RuntimeException($errorMessage);
                }
            }
        } while ($isConnected === false && $attemptCount++ < $maxAttempt);

        $output->writeln('<success>Successfully connected to database</success>');

        $dbnameQuestion = new Question('Please enter the database name (default: Manager): ',
            'Manager');
        $dbnameQuestion
            ->setMaxAttempts($maxAttempt)
            ->setValidator(function ($answer) {
                //if db does not exists try to create it
                $connection = Connection::getInstance();
                try {
                    $connection->select($answer);
                } catch (DatabaseException $e) {
                    try {
                        $connection->execute('CREATE DATABASE IF NOT EXISTS `%s`', $answer);
                    } catch (DatabaseException $e) {
                        throw new RuntimeException(sprintf('Database %s does not exists and can not be created',
                            $answer));
                    }
                }
                return $answer;
            });
        $config->database = $questionHelper->ask($input, $output, $dbnameQuestion);
    }

    protected function configureManiaConnect(InputInterface $input, OutputInterface $output)
    {
        /* @var $questionHelper QuestionHelper */
        $questionHelper = $this->getHelper('question');

        $config       = WSConfig::getInstance();
        $maxAttempt   = 3;
        $attemptCount = 1;
        $isConnected  = false;

        $userQuestion = new Question('Please enter your Maniaplanet Webservices username: ');
        $passQuestion = new Question('Please enter your Maniaplanet Webservices password: ');
        $passQuestion
            ->setHidden(true)
            ->setHiddenFallback(true);

        $output->writeln('<info>Maniaplanet Webservices configuration</info>');
        do {
            $config->username = $questionHelper->ask($input, $output, $userQuestion);
            $config->password = $questionHelper->ask($input, $output, $passQuestion);

            try {
                $wsManialink = new Manialinks();
                $wsManialink->get('maniahome');
                $isConnected = true;
            } catch (WSException $e) {
                $errorMessage = 'Invalid Maniaplanet API credentials';
                if ($attemptCount < $maxAttempt) {
                    $output->writeln(sprintf('<error>%s</error>', $errorMessage));
                } else {
                    throw new RuntimeException($errorMessage);
                }
            }
        } while ($isConnected === false && $attemptCount++ < $maxAttempt);
        $output->writeln('<success>Successfully connected to Maniaplanet webservices</success>');
    }

    protected function createManagerSchema(OutputInterface $output)
    {
        $database = DatabaseConfig::getInstance()->database;
        $this->createTable(
            'Servers',
            <<<EOSQL
CREATE TABLE $database.Servers (
  `name` varchar(75) NOT NULL,
  `rpcHost` varchar(25) NOT NULL,
  `rpcPort` smallint(5) unsigned NOT NULL,
  `rpcPassword` varchar(50) NOT NULL,
  PRIMARY KEY (`rpcHost`,`rpcPort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOSQL
            , $output);
        $this->createTable(
            'Managers',
            <<<EOSQL
CREATE TABLE IF NOT EXISTS $database.Managers (
  `login` varchar(25) NOT NULL,
  `rpcHost` varchar(25) NOT NULL,
  `rpcPort` smallint(5) unsigned NOT NULL,
  UNIQUE KEY `login_rpcHost_rpcPort` (`login`,`rpcHost`,`rpcPort`),
  KEY `managerServer` (`rpcHost`,`rpcPort`),
  CONSTRAINT `managerServer` FOREIGN KEY (`rpcHost`, `rpcPort`) REFERENCES `Servers` (`rpcHost`, `rpcPort`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOSQL
            , $output);
        $this->createTable(
            'Maps',
            <<<EOSQL
CREATE TABLE IF NOT EXISTS $database.Maps (
  `path` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `uid` char(27) NOT NULL,
  `name` varchar(75) NOT NULL,
  `environment` varchar(15) NOT NULL,
  `mood` varchar(15) NOT NULL,
  `type` varchar(50) NOT NULL,
  `displayCost` int(10) unsigned NOT NULL,
  `nbLaps` int(10) unsigned NOT NULL DEFAULT '0',
  `authorLogin` varchar(25) NOT NULL,
  `authorNick` varchar(75) DEFAULT NULL,
  `authorZone` varchar(255) DEFAULT NULL,
  `authorTime` int(11) DEFAULT NULL,
  `goldTime` int(11) DEFAULT NULL,
  `silverTime` int(11) DEFAULT NULL,
  `bronzeTime` int(11) DEFAULT NULL,
  `authorScore` int(11) DEFAULT NULL,
  `size` int(10) unsigned NOT NULL,
  `mTime` datetime NOT NULL,
  PRIMARY KEY (`path`,`filename`),
  KEY `mTime` (`mTime`),
  KEY `size` (`size`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOSQL
            , $output);
    }

    protected function checkItTableExists($tableName)
    {
        $connection = Connection::getInstance();
        try {
            $connection->execute(
                'SELECT * FROM %s.%s LIMIT 1',
                DatabaseConfig::getInstance()->database,
                $tableName
            );
            return true;
        } catch (DatabaseException $ex) {
            return false;
        }
    }

    protected function createTable($tableName, $query, OutputInterface $output)
    {
        $connection = Connection::getInstance();
        if (!$this->checkItTableExists($tableName)) {
            $output->writeln(sprintf('<info>Creating %s table</info>', $tableName));
            $connection->execute($query);
            $output->writeln(sprintf('<success>Table %s created</success>', $tableName));
        } else {
            $output->writeln(sprintf('<info>Table %s already exists</info>', $tableName));
        }
    }

    protected function saveConfiguration(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Saving your configuration in app.ini file</info>');

        $applicationConfig      = ApplicationConfig::getInstance();
        $dedicatedManagerConfig = DedicatedManagerConfig::getInstance();
        $webServicesConfig      = WSConfig::getInstance();
        $databaseConfig         = DatabaseConfig::getInstance();

        $config = "application.namespace = DedicatedManager\n";
        $config .= "application.webapp = true\n";
        $config .= sprintf("application.URL = '%s'\n", $applicationConfig->URL.'/');
        $config .= "DedicatedManager\Config.maniaConnect = On\n";
        $config .= sprintf("DedicatedManager\Config.dedicatedPath = '%s'\n", $dedicatedManagerConfig->dedicatedPath.'/');
        foreach ($dedicatedManagerConfig->admins as $admin) {
            $config .= sprintf("DedicatedManager\Config.admins[] = %s\n", $admin);
        }
        $config .= sprintf("webservices.username = '%s'\n", $webServicesConfig->username);
        $config .= sprintf("webservices.password = '%s'\n", $webServicesConfig->password);

        $config .= sprintf("database.host = '%s'\n", $databaseConfig->host);
        $config .= sprintf("database.user = '%s'\n", $databaseConfig->user);
        $config .= sprintf("database.password = '%s'\n", $databaseConfig->password);
        $config .= sprintf("database.database = '%s'\n", $databaseConfig->database);
        $config .= "database.slowQueryLog = true\n";

        file_put_contents('./config/app.ini', $config);
        $output->writeln('<success>Your Dedicated manager is successfully configured</success>');
        $output->writeln(sprintf('<success>You can now access to: %s</success>', $applicationConfig->URL));
    }
}