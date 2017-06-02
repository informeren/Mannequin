<?php

namespace LastCall\Mannequin\Twig\Tests\Subscriber;

use LastCall\Mannequin\Core\Pattern\PatternCollection;
use LastCall\Mannequin\Twig\Pattern\TwigPattern;
use LastCall\Mannequin\Twig\Subscriber\TwigIncludeSubscriber;
use LastCall\Mannequin\Twig\TwigInspector;
use PHPUnit\Framework\TestCase;
use LastCall\Mannequin\Core\Tests\Subscriber\DiscoverySubscriberTestTrait;

class TwigIncludeSubscriberTest extends TestCase {
  use DiscoverySubscriberTestTrait;

  public function testRunsDetection() {
    $twigSrc = new \Twig_Source('', '', '');

    $inspector = $this->prophesize(TwigInspector::class);
    $inspector->inspectLinked($twigSrc)
      ->willReturn(['bar'])
      ->shouldBeCalled();

    $collection = $this->prophesize(PatternCollection::class);
    $collection->has('twig://bar')
      ->willReturn(TRUE)
      ->shouldBeCalled();
    $collection->get('twig://bar')
      ->willReturn(new TwigPattern('bar', [], $twigSrc))
      ->shouldBeCalled();

    $pattern = new TwigPattern('foo', [], new \Twig_Source('', '', ''));
    $subscriber = new TwigIncludeSubscriber($inspector->reveal());
    $this->dispatchDiscover($subscriber, $pattern, $collection->reveal());
  }

}