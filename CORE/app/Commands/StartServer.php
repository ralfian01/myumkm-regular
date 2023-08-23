<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class StartServer extends BaseCommand
{

    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'start_server';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'command:name [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {

        $_SERVER['argv'][2] = '--host';
        $_SERVER['argv'][3] = getenv('LOCAL_SERVER_IP');

        $_SERVER['argv'][4] = '--port';
        $_SERVER['argv'][5] = getenv('LOCAL_PORT');
        $_SERVER['argc']    = 6;
        CLI::init();

        $this->call('serve');
    }
}
