<?php



namespace Vartruexuan\EasyOutApi\Kernel\Events;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class ServerGuardResponseCreated.
 *
 * @author mingyoung <mingyoungcheung@gmail.com>
 */
class ServerGuardResponseCreated
{
    /**
     * @var \Symfony\Component\HttpFoundation\Response
     */
    public $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }
}
