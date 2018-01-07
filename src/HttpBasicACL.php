<?php 

namespace Slim\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpBasicACL
{
    /**
     * Allowed
     *
     * @var mixed
     */
    protected $_allowed = null;
    
    public function __construct($allowed) 
    {
        if ( !is_array($allowed) && !is_callable($allowed) ) {
            throw new InvalidArgumentException('Allowed must be array or callable');
        }
        
        $this->_allowed = $allowed;
    }
    
    /**
     * 
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        
        if ( $this->_allowed != null ) {
            if ( is_array($this->_allowed) ) {
                if ( !in_array($request->getServerParams()["PHP_AUTH_USER"], $this->_allowed) ) {
                    return $response->withStatus(403);
                }
            }
            
            if (is_callable($this->_allowed)) {
                $method = $this->_allowed;
                if (false === $method($request, $response)) {
                    return $response->withStatus(403);
                }
            }
        }
        
        $response = $next($request, $response);        
        
        return $response;
    }
}
