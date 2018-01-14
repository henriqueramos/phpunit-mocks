<?php

class HelloWorldTest extends TestCase
{
	/**
     * @var string
     */
    protected $requestPrefix = 'my-endpoint';
	
    protected function setUp()
    {
        parent::setUp();
        parent::setupMockServer('localhost', '9876', 'HelloWorld/');
    }

    protected function tearDown()
    {
        parent::tearDownAfterClass();
        parent::killMockServer();
    }
	
	/**
     * How to use mocks with env variables
     *
     * @return void
     */
    public function testHowToUseMocks()
    {
		//Set the new mock endpoint for your partner
        putenv('YOUR_PARTNER_SERVICE_URL=http://localhost:9876/index.php');
		
		//Now call your script as usual
		$response = $this->call('POST', '');
		
		$actual = $response->getStatusCode();
        $expected = 200;
        $this->assertEquals($expected, $actual, 'Something went wrong. ' . print_r($response->getContent(), true));
	}
}
