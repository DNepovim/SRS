<?php

declare(strict_types=1);

namespace App\Services;

use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Kdyby\Console\Application;
use Kdyby\Console\StringOutput;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\DI\Container;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Služba pro správu databáze.
 *
 * @author Jan Staněk <jan.stanek@skaut.cz>
 */
class DatabaseService
{
    /** @var Application */
    public $application;

    /** @var Container */
    public $container;

    /** @var string */
    public $dir;

    /** @var Cache */
    protected $cache;


    public function __construct(string $dir, Application $application, Container $container, IStorage $storage)
    {
        $this->dir         = $dir;
        $this->application = $application;
        $this->container   = $container;

        $this->cache = new Cache($storage, 'Database');
    }

    /**
     * Vytvoří zálohu databáze a spustí migrace. Spouští se pouze pokud není v cache záznam o provedeném update.
     * @throws \Throwable
     */
    public function update() : void
    {
        if ($this->cache->load('updated') !== null) {
            return;
        }

        $this->cache->save('lock', function () {
            if ($this->cache->load('updated') === null) {
                $this->cache->save('updated', new \DateTime());

                $this->backup();

                $this->application->add(new MigrateCommand());
                $output = new StringOutput();
                $input  = new ArrayInput([
                    'command' => 'migrations:migrate',
                    '--no-interaction' => true,
                ]);
                $this->application->run($input, $output);
            }
            return true;
        });
    }

    /**
     * Vytvoří zálohu databáze.
     * @throws \Exception
     */
    public function backup() : void
    {
        $database = $this->container->parameters['database'];

        $host     = $database['host'];
        $user     = $database['user'];
        $password = $database['password'];
        $dbname   = $database['dbname'];

        $dump = new \MySQLDump(new \mysqli($host, $user, $password, $dbname));

        $timestamp = (new \DateTime())->format('YmdHi');

        $dump->save($this->dir . '/backup/' . $dbname . '-' . $timestamp . '.sql.gz');
    }
}
