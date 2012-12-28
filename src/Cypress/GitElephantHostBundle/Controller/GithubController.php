<?php
/**
 * User: matteo
 * Date: 28/12/12
 * Time: 9.21
 * 
 * Just for fun...
 */

namespace Cypress\GitElephantHostBundle\Controller;

use Buzz\Browser;
use Cypress\GitElephantHostBundle\Controller\Base\Controller as BaseController;
use Cypress\GitElephantHostBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Buzz\Message\Request as BuzzRequest;

/**
 * controller for github
 *
 * @Route("/github")
 */
class GithubController extends BaseController
{
    /**
     * main page
     *
     * @Route("/login", name="github_login")
     */
    public function loginAction()
    {
        $user = new User();
        $this->getEM()->persist($user);
        $this->getEM()->flush();
        $loginUrl = $this->container->getParameter('cypress_git_elephant_host.login_url');

        return new RedirectResponse($loginUrl.'?'.http_build_query($this->buildArrayQuery($user)));
    }

    /**
     * @Route("/response", name="github_response")
     */
    public function githubResponseAction(Request $request)
    {
        $code = $request->query->get('code');
        $state = $request->query->get('state');
        $user = $this->getEM()->getRepository('Cypress\GitElephantHostBundle\Entity\User')->findOneBy(array('token' => $state));
        $accessTokenUrl = $this->container->getParameter('cypress_git_elephant_host.access_token_url');
        $request = new \Buzz\Message\Request(BuzzRequest::METHOD_POST, '/', $accessTokenUrl);
        $request->setContent(http_build_query($this->buildArrayQuery($user, $code)));
        $request->setHeaders(array(
            'accept' => 'application/json'
        ));
        $response = new \Buzz\Message\Response();
        $client = new \Buzz\Client\Curl();
        $client->send($request, $response);
        $githubData = json_decode($response->getContent());
        $user->setAccessToken($githubData->access_token);
        $this->getEM()->persist($user);
        $this->getEM()->flush();
        $this->getSession()->set('user_id', $user->getId());

        return new RedirectResponse($this->generateUrl('homepage'));
    }

    private function buildArrayQuery(User $user, $code = null)
    {
        $clientId = $this->container->getParameter('cypress_git_elephant_host.client_id');
        $clientSecret = $this->container->getParameter('cypress_git_elephant_host.client_secret');
        $redirectUri = $this->generateUrl('github_response', array(), true);
        $state = $user->getToken();
        $data = array();
        $data = array(
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'state' => $state,
            'redirect_uri' => $redirectUri
        );
        if (null !== $code) {
            $data['code'] = $code;
        }

        return $data;
    }
}
