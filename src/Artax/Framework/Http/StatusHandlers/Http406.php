<?php
/**
 * Http406 Handler Class File
 * 
 * @category     Artax
 * @package      Framework
 * @subpackage   Http
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 * @license      All code subject to the terms of the LICENSE file in the project root
 * @version      ${project.version}
 */
namespace Artax\Framework\Http\StatusHandlers;

use Artax\Events\Mediator,
    Artax\Http\Request,
    Artax\Http\Response,
    Artax\Http\StatusCodes,
    Artax\Negotiation\NotAcceptableException;

/**
 * A default handler for 406 scenarios
 * 
 * @category     Artax
 * @package      Framework
 * @subpackage   Http
 * @author       Daniel Lowrey <rdlowrey@gmail.com>
 */
class Http406 {
    
    /**
     * @var Mediator
     */
    private $mediator;
    
    /**
     * @var Request
     */
    private $request;
    
    /**
     * @var Response
     */
    private $response;
    
    /**
     * Constructor
     * 
     * @param Mediator $mediator
     * @param Request $request
     * @param Response $response
     * 
     * @return void
     */
    public function __construct(Mediator $mediator, Request $request, Response $response) {
        $this->mediator = $mediator;
        $this->request  = $request;
        $this->response = $response;
    }
    
    /**
     * Builds and outputs a 406 response
     * 
     * @return void
     */
    public function __invoke(NotAcceptableException $e) {
        $this->response->setStatusCode(StatusCodes::HTTP_NOT_ACCEPTABLE);
        $this->response->setStatusDescription('Not Acceptable');
        
        $userEvent = 'app.http-' . StatusCodes::HTTP_NOT_ACCEPTABLE;
        
        if (!$this->mediator->notify($userEvent, $this->request, $this->response, $e)) {
            $body  = '<h1>406 Not Acceptable</h1>' . PHP_EOL . '<hr />' . PHP_EOL;
            $body .= '<p>' . $e->getMessage() . '</p>' . PHP_EOL;
            $this->response->setBody($body);
            $this->response->setHeader('Content-Type', 'text/html');
            $this->response->setHeader('Content-Length', strlen($body));
        }
        
        if (!$this->response->wasSent()) {
            $this->response->send();
        }
        
        return false;
    }
}
