<?php



namespace Vartruexuan\EasyOutApi\Kernel\Events;

use Vartruexuan\EasyOutApi\Kernel\ServiceContainer;

/**
 * Class ApplicationInitialized.
 *
 * @author mingyoung <mingyoungcheung@gmail.com>
 */
class ApplicationInitialized
{
    /**
     * @var \Vartruexuan\EasyOutApi\Kernel\ServiceContainer
     */
    public $app;

    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }
}
