<?php
/**
 * User: matteo
 * Date: 04/12/12
 * Time: 0.46
 * 
 * Just for fun...
 */

namespace Cypress\PygmentsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{
    public function cssAction()
    {
        $pygemntize = $this->get('pygments_elephant.pygmentize');
        $response = new Response();
        $response->headers->set('content-type', 'text/css');
        $response->setContent($pygemntize->generateCss());
        return $response;
    }
}
