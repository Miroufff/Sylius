<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\ResourceBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Form\DataTransformer\RecursiveTransformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class RecursiveTransformerSpec extends ObjectBehavior
{
    function let(DataTransformerInterface $decoratedTransformer)
    {
        $this->beConstructedWith($decoratedTransformer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RecursiveTransformer::class);
    }

    function it_is_data_transformer()
    {
        $this->shouldImplement(DataTransformerInterface::class);
    }

    function it_transforms_recursively_using_configured_transformer(DataTransformerInterface $decoratedTransformer)
    {
        $decoratedTransformer->transform('ABC')->willReturn('abc');
        $decoratedTransformer->transform('CDE')->willReturn('cde');
        $decoratedTransformer->transform('FGH')->willReturn('fgh');

        $this->transform(new ArrayCollection(['ABC', 'CDE', 'FGH']))->shouldBeLike(new ArrayCollection(['abc', 'cde', 'fgh']));
    }

    function it_reverse_transforms_using_configured_transformer(DataTransformerInterface $decoratedTransformer)
    {
        $decoratedTransformer->reverseTransform('abc')->willReturn('ABC');
        $decoratedTransformer->reverseTransform('cde')->willReturn('CDE');
        $decoratedTransformer->reverseTransform('fgh')->willReturn('FGH');

        $this->reverseTransform(new ArrayCollection(['abc', 'cde', 'fgh']))->shouldBeLike(new ArrayCollection(['ABC', 'CDE', 'FGH']));
    }

    function it_throws_transformation_failed_exception_if_transform_argument_is_not_collection()
    {
        $this->shouldThrow(TransformationFailedException::class)->during('transform', [new \stdClass()]);
        $this->shouldThrow(TransformationFailedException::class)->during('reverseTransform', [new \stdClass()]);
    }
}
