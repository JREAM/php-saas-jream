<?php

use Phalcon\Cli\Task;

class MainTask extends Task
{

    public function mainAction()
    {
        echo ' - Run with: php app/cli.php main {taskname} {param}' . PHP_EOL;
        echo '      - Example: php app/cli.php newsletter send' . PHP_EOL;
    }

    // -----------------------------------------------------------------------------

    /**
     * @param array $params
     */
    public function testAction(array $params)
    {
        echo sprintf('hello %s', $params[ 0 ]);

        echo PHP_EOL;

        echo sprintf('best regards, %s', $params[ 1 ]);

        echo PHP_EOL;
    }
}
