<?php

/*
 * This file is part of the Pathogen package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Pathogen\Resolver;

use Eloquent\Pathogen\Factory\PathFactory;


class FixedBasePathResolverTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new PathFactory;

        $this->basePath = $this->factory->create('/foo/bar');
        $this->innerResolver = new BasePathResolver;
        $this->resolver = new FixedBasePathResolver($this->basePath, $this->innerResolver);
    }

    public function testConstructor()
    {
        $this->assertSame($this->basePath, $this->resolver->basePath());
        $this->assertSame($this->innerResolver, $this->resolver->resolver());
    }

    public function testConstructorDefaults()
    {
        $this->resolver = new FixedBasePathResolver($this->basePath);

        $this->assertInstanceOf(__NAMESPACE__ . '\BasePathResolver', $this->resolver->resolver());
    }

    public function testResolve()
    {
        $path = $this->factory->create('baz/qux');
        $resolvedPath = $this->resolver->resolve($path);

        $this->assertSame('/foo/bar/baz/qux', $resolvedPath->string());
    }
}
