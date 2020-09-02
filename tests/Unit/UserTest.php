<?php


class UserTest extends \PHPUnit\Framework\TestCase
{


    public function testActivateAccountShouldCallSignDocumentWithSpecificArguments()
    {

        $docuSignMock = Mockery::mock(\App\Services\DocuSignService::class);
        $std = new stdClass();
        $std->status = 'verified';

        $docuSignMock->shouldReceive('signDocument')->with(12345, 12345)
            ->andReturn($std);

        $userController = new \App\Controller\UserController($docuSignMock, new \App\Models\User());

        $userController->activateAccount(12345, 12345);
    }

    public function testSignDocumentShouldCallRequest()
    {

        $mockedSoapClient = Mockery::mock('overload:'.\App\Services\DocuSignSoap::class);
        $mockedSoapClient->shouldReceive('request')->withAnyArgs();

        $docuSignedService = new \App\Services\DocuSignService();
        $docuSignedService->signDocument(12345, 12345);
    }

    public function testDocuSignWsdlShouldCallRequestWithSpecificArgument()
    {
        $mockedWSDL = $this->getMockFromWsdl(dirname(__DIR__, 2).'/app/Services/docusign.wsdl');
        $mockedWSDL->expects($this->once())->method('request')->with(12345)->willReturn([]);

        $this->assertEquals([], $mockedWSDL->request(12345));
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $container = Mockery::getContainer();

        $this->addToAssertionCount($container->mockery_getExpectationCount());

        Mockery::close();
    }

}