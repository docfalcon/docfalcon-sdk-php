<?php

namespace DocFalcon\Tests;

use DocFalcon\Client;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /** @var vfsStreamDirectory */
    private $fs;

    public function setUp()
    {
        $this->fs = vfsStream::setup();
    }

    public function testGenerateMissingDocument()
    {
        $client = $this->getMock('\GuzzleHttp\Client');
        $sut = new Client($client, 'apikey');
        $this->setExpectedException('DocFalcon\Exception', 'Missing document');
        $sut->generate(null, null);
    }

    public function testGenerateMissingPath()
    {
        $client = $this->getMock('\GuzzleHttp\Client');
        $sut = new Client($client, 'apikey');
        $this->setExpectedException('DocFalcon\Exception', 'Missing pdf save path');
        $sut->generate(array('document' => array()), null);
    }

    public function testGenerateRequestException()
    {
        $filename = 'file.pdf';
        $exception = $this->getMock('GuzzleHttp\Exception\TransferException',
            array(), array('Request error')
        );
        $guzzleClient = $this->getMock('GuzzleHttp\Client', array('post'));
        $guzzleClient
            ->expects($this->once())
            ->method('post')
            ->with('https://www.docfalcon.com/api/v1/pdf?apikey=apikey')
            ->willThrowException($exception);

        $sut = new Client($guzzleClient, 'apikey');
        $this->setExpectedException('DocFalcon\Exception', 'Request error');
        $sut->generate(array('document' => array()), $this->fs->url() . '/' . $filename);
    }

    public function testGenerateResponseException()
    {
        $filename = 'file.pdf';
        $response = $this->getMock('Psr\Http\Message\ResponseInterface');
        $response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(400);
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn('{ "message": "Not found" }');
        $guzzleClient = $this->getMock('GuzzleHttp\Client', array('post'));
        $guzzleClient
            ->expects($this->once())
            ->method('post')
            ->with('https://www.docfalcon.com/api/v1/pdf?apikey=apikey')
            ->willReturn($response);

        $sut = new Client($guzzleClient, 'apikey');
        $this->setExpectedException('DocFalcon\Exception', 'Not found');
        $sut->generate(array('document' => array()), $this->fs->url() . '/' . $filename);
    }

    public function testGenerateSuccessful()
    {
        $filename = 'file.pdf';
        $response = $this->getMock('Psr\Http\Message\ResponseInterface');
        $response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn('pdf');
        $guzzleClient = $this->getMock('GuzzleHttp\Client', array('post'));
        $guzzleClient
            ->expects($this->once())
            ->method('post')
            ->with('https://www.docfalcon.com/api/v1/pdf?apikey=apikey')
            ->willReturn($response);

        $sut = new Client($guzzleClient, 'apikey');
        $this->assertFalse($this->fs->hasChild($filename));
        $sut->generate(array('document' => array()), $this->fs->url() . '/' . $filename);

    }
}