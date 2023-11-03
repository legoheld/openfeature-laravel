<?php

namespace OpenFeature\Laravel\Tests;

use Illuminate\Support\Facades\App;
use Mockery;
use OpenFeature\OpenFeature;
use OpenFeature\interfaces\provider\Provider as OpenFeatureProvider;
use OpenFeature\OpenFeatureAPI;
use Illuminate\Support\Facades\Config;
use Mockery\MockInterface;
use OpenFeature\implementation\flags\Attributes;
use OpenFeature\implementation\flags\EvaluationContext;
use OpenFeature\implementation\flags\MutableEvaluationContext;
use OpenFeature\implementation\provider\ResolutionDetailsFactory;
use OpenFeature\Mappers\ContextMapper;
use PHPUnit\Framework\TestCase;

class OpenFeatureTest extends TestCase
{

    protected MockInterface $mockProvider;

    protected function setUp(): void
    {
        $this->mockProvider = Mockery::mock(OpenFeatureProvider::class);

        $this->mockProvider->shouldReceive('getMetadata');
        $this->mockProvider->shouldReceive('getHooks');

        OpenFeatureAPI::getInstance()->setProvider( $this->mockProvider );
    }


    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testConfigContext() 
    {

        Config::shouldReceive('has')
            ->with( 'openfeature.clients.demo' )
            ->once()
            ->andReturn(true);

        Config::shouldReceive('get')
            ->with( 'openfeature.clients.demo.mapper' )
            ->once()
            ->andReturn(null);

        Config::shouldReceive('get')
            ->with( 'openfeature.clients.demo.context', [] )
            ->once()
            ->andReturn([ 'environment' => 'test' ]);

        $capturedContext = null;

        $this->mockProvider->shouldReceive('resolveBooleanValue')
            ->once()  // expecting the method to be called once
            ->with( 'flag', false, Mockery::capture($capturedContext) )
            ->andReturn( ResolutionDetailsFactory::fromSuccess( true ) );

        $features = OpenFeature::fromConfig( 'demo' );
        $result = $features->boolean( 'flag', false );

        $this->assertInstanceOf( MutableEvaluationContext::class, $capturedContext );
        $this->assertEquals( [ 'environment' => 'test' ], $capturedContext->getAttributes()->toArray() );

    }


    public function testConfigMapperWithScope() 
    {
        $demoContext = [ 'environment' => 'test' ];
        $mockScope = Mockery::mock();

        $mockMapper = Mockery::mock(ContextMapper::class);
        $mockMapper->shouldReceive( 'map')
            ->with( $demoContext, NULL )
            ->andReturn( new EvaluationContext( '', new Attributes( [] ) ) );

        $mockMapper->shouldReceive( 'map')
            ->with( $demoContext, $mockScope )
            ->andReturn( new EvaluationContext( 'id', new Attributes( $demoContext ) ) );

        App::shouldReceive( 'make' )->andReturn( $mockMapper );
        Config::shouldReceive('has')->andReturn(true);

        Config::shouldReceive('get')
            ->with( 'openfeature.clients.demo.mapper' )
            ->andReturn( 'my-mapper' );

        Config::shouldReceive('get')
            ->with( 'openfeature.clients.demo.context', [] )
            ->andReturn([ 'environment' => 'test' ]);

        $capturedContext = null;

        $this->mockProvider->shouldReceive('resolveBooleanValue')
            ->once()  // expecting the method to be called once
            ->with( 'flag', false, Mockery::capture($capturedContext) )
            ->andReturn( ResolutionDetailsFactory::fromSuccess( true ) );

        $features = OpenFeature::fromConfig( 'demo' )->for( $mockScope );
        $result = $features->boolean( 'flag', false );

        $this->assertInstanceOf( MutableEvaluationContext::class, $capturedContext );
        $this->assertEquals( [ 'environment' => 'test' ], $capturedContext->getAttributes()->toArray() );
        $this->assertEquals( $capturedContext->getTargetingKey(), 'id' );

    }

    // ... You can continue writing tests for other methods in a similar fashion



    public function testBooleanMethod()
    {
        $this->mockProvider->shouldReceive('resolveBooleanValue')
            ->once()  // expecting the method to be called once
            ->with( 'someFlag', false, Mockery::type(MutableEvaluationContext::class) )
            ->andReturn( ResolutionDetailsFactory::fromSuccess( true ) );

        $openFeature = new OpenFeature( OpenFeatureAPI::getInstance()->getClient() );

        $result = $openFeature->boolean('someFlag', false);

        $this->assertTrue($result);
    }
    


    public function testIntegerMethod()
    {
        $this->mockProvider->shouldReceive('resolveIntegerValue')
            ->once()  // expecting the method to be called once
            ->with( 'someFlag', 20, Mockery::type(MutableEvaluationContext::class) )
            ->andReturn( ResolutionDetailsFactory::fromSuccess( 30 ) );

        $openFeature = new OpenFeature( OpenFeatureAPI::getInstance()->getClient() );

        $result = $openFeature->integer('someFlag', 20);

        $this->assertEquals( 30, $result);
    }



    public function testStringMethod()
    {
        $this->mockProvider->shouldReceive('resolveStringValue')
            ->once()  // expecting the method to be called once
            ->with( 'someFlag', 'fallback', Mockery::type(MutableEvaluationContext::class) )
            ->andReturn( ResolutionDetailsFactory::fromSuccess( 'flagvalue' ) );

        $openFeature = new OpenFeature( OpenFeatureAPI::getInstance()->getClient() );

        $result = $openFeature->string('someFlag', 'fallback');

        $this->assertEquals( 'flagvalue', $result);
    }



    public function testFloatMethod()
    {
        $this->mockProvider->shouldReceive('resolveFloatValue')
            ->once()  // expecting the method to be called once
            ->with( 'someFlag', 0.244, Mockery::type(MutableEvaluationContext::class) )
            ->andReturn( ResolutionDetailsFactory::fromSuccess( 3.81 ) );

        $openFeature = new OpenFeature( OpenFeatureAPI::getInstance()->getClient() );

        $result = $openFeature->float('someFlag', 0.244);

        $this->assertEquals( 3.81, $result);
    }
    


    public function testObjectMethod()
    {
        $this->mockProvider->shouldReceive('resolveObjectValue')
            ->once()  // expecting the method to be called once
            ->with( 'someFlag', [ 'demo' => 12 ], Mockery::type(MutableEvaluationContext::class) )
            ->andReturn( ResolutionDetailsFactory::fromSuccess( [ 'demo' => 'test '] ) );

        $openFeature = new OpenFeature( OpenFeatureAPI::getInstance()->getClient() );

        $result = $openFeature->object('someFlag', [ 'demo' => 12 ]);

        $this->assertEquals( [ 'demo' => 'test '], $result);
    }

    
}
