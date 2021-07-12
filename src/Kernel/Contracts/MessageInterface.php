<?php



namespace Vartruexuan\EasyOutApi\Kernel\Contracts;

/**
 * Interface MessageInterface.
 *
 * @author overtrue <i@overtrue.me>
 */
interface MessageInterface
{
    public function getType(): string;

    public function transformForJsonRequest(): array;

    public function transformToXml(): string;
}
