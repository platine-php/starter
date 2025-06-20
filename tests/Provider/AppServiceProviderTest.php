<?php

declare(strict_types=1);

namespace Platine\Test\App\Provider;

use Platine\App\Provider\AppServiceProvider;
use Platine\Dev\PlatineTestCase;
use Platine\Framework\App\Application;

/*
 * @group core
 * @group app
 */
class AppServiceProviderTest extends PlatineTestCase
{
    public function testRegister(): void
    {
        $app = $this->getMockInstanceMap(Application::class);

        $this->expectMethodCallCount($app, 'bind', 3);

        $o = new AppServiceProvider($app);
        $o->register();
    }
}
