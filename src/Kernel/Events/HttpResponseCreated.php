<?php



namespace Vartruexuan\EasyOutApi\Kernel\Events;

use Psr\Http\Message\ResponseInterface;

/**
 * Class HttpResponseCreated.
 *
 * @author mingyoung <mingyoungcheung@gmail.com>
 */
class HttpResponseCreated
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    public $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }
}
