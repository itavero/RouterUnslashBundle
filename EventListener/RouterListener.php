<?php

/**
 * This file is part of the Router Unslash Bundle.
 * 
 * Copyright (c) 2013, Arno Moonen <info@arnom.nl>
 * 
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 * 
 * @author Arno Moonen <info@arnom.nl>
 * @copyright Copyright (c) 2013, Arno Moonen <info@arnom.nl>
 */

namespace AMNL\RouterUnslashBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * RouterListener class
 *
 * @author Arno Moonen <info@arnom.nl>
 */
class RouterListener
{

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var boolean Redirect as 301? 
     */
    protected $permanentRedirect;

    /**
     * @var boolean Public cache?
     */
    protected $publicCache;

    /**
     * @var int Max Age
     */
    protected $cacheMaxAge;

    /**
     * @var int Shared Max Age
     */
    protected $cacheSharedMaxAge;

    public function __construct(Router $r, $permanent, $public, $maxage = null, $smaxage = null)
    {
        $this->router = $r;
        $this->permanentRedirect = $permanent;
        $this->publicCache = $public;
        $this->cacheMaxAge = $maxage;
        $this->cacheSharedMaxAge = $smaxage;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
            // Don't do anything if it's not the master request
            return;
        }

        if ($this->hasResourceNotFoundException($event->getException())) {
            // Resource not found
            $req = $event->getRequest();
            $pathInfo = $req->getPathInfo();

            if (substr($pathInfo, -1) === '/') {
                // Currently ends with a slash -> remove it
                $pathInfo = substr($pathInfo, 0, -1);
            } else {
                // Append a slash
                $pathInfo .= '/';
            }

            try {
                $result = $this->router->match($pathInfo);
                if (count($result) > 0) {
                    // Construct alternative URI
                    if (null !== $qs = $req->getQueryString()) {
                        $qs = '?' . $qs;
                    }
                    $altUri = $req->getBaseUrl() . $pathInfo . $qs;

                    // Create redirect response
                    $rsp = new Response('', ($this->permanentRedirect) ? 301 : 302, array(
                        'Location' => $altUri,
                    ));

                    $rsp->setPublic($this->publicCache);

                    if ($this->cacheMaxAge != null) {
                        $rsp->setMaxAge($this->cacheMaxAge);
                    }

                    if ($this->cacheSharedMaxAge != null) {
                        $rsp->setSharedMaxAge($this->cacheSharedMaxAge);
                    }

                    $event->setResponse($rsp);
                }
            }
            catch (\Exception $ex) {
                // Alternative URL not found; don't do anything
            }
        }
    }

    /**
     *
     * @param \Exception $ex Exception that needs to be checked
     * @return boolean True if a ResourceNotFoundException is found
     */
    protected function hasResourceNotFoundException(\Exception $ex)
    {
        if ($ex instanceof ResourceNotFoundException) {
            return true;
        } elseif ($ex->getPrevious() != null) {
            return $this->hasResourceNotFoundException($ex->getPrevious());
        }

        return false;
    }

}