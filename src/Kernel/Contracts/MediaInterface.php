<?php



namespace Vartruexuan\EasyOutApi\Kernel\Contracts;

/**
 * Interface MediaInterface.
 *
 * @author overtrue <i@overtrue.me>
 */
interface MediaInterface extends MessageInterface
{
    public function getMediaId(): string;
}
