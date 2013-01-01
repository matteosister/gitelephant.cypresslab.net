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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Buzz\Message\Request as BuzzRequest;
use Buzz\Client\Curl;

/**
 * controller for github
 *
 * @Route("/github")
 */
class GithubController extends BaseController
{
    /**
     * github user
     *
     * @return array
     * @Route("/user.{_format}",
     *   name="github_user",
     *   options={"expose"=true},
     *   defaults={"_format"="json"}
     * )
     * @Template
     */
    public function userAction()
    {
        $user = $this->getUser();

        return compact('user');
    }

    /**
     * github user repositories
     *
     * @return array
     * @Route("/repositories.{_format}",
     *   name="github_repositories",
     *   options={"expose"=true},
     *   defaults={"_format"="html"}
     * )
     * @Template
     */
    public function repositoriesAction()
    {
        if ('json' === $this->getRequest()->getRequestFormat()) {
            $response = $this->getGihubUser()->getRepositories();

            return $response;
        }

        return array();
    }

    /**
     * github user repositories pagination
     *
     * @return array
     * @Route("/repositories/pagination",
     *   name="github_repositories_pagination",
     *   options={"expose"=true}
     * )
     * @Template
     */
    public function linkPaginationAction()
    {
        $response = $this->getGihubUser()->getRepositories();
        $links = $this->getLinkHeader($response);

        return compact('links');
    }

    /**
     * login
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
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
     * logout
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/logout", name="github_logout")
     */
    public function logoutAction()
    {
        $response = new RedirectResponse($this->generateUrl('homepage'));
        $response->headers->clearCookie('user');

        return $response;
    }

    /**
     * github response
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/response", name="github_response")
     */
    public function githubResponseAction(Request $request)
    {
        $response = new RedirectResponse($this->generateUrl('homepage'));
        $code = $request->query->get('code');
        $state = $request->query->get('state');
        $user = $this->getEM()->getRepository('Cypress\GitElephantHostBundle\Entity\User')->findOneBy(array('token' => $state));
        $accessToken = $this->getAccessToken($user, $code);
        if (null !== $oldUser = $this->getUserRepository()->findOneBy(array('accessToken' => $accessToken))) {
            $this->getEM()->remove($user);
            $this->getEM()->flush();
            $this->setUserCookie($response, $oldUser);
        } else {
            $user->setAccessToken($accessToken);
            $this->getEM()->persist($user);
            $this->getEM()->flush();
            $this->setUserCookie($response, $user);
        }

        return $response;
    }

    /**
     * set cookie for the user
     *
     * @param \Symfony\Component\HttpFoundation\Response $response response
     * @param \Cypress\GitElephantHostBundle\Entity\User $user     user
     */
    private function setUserCookie(Response $response, User $user)
    {
        $expire = new \DateTime();
        $expire->add(new \DateInterval('P6M'));
        $response->headers->setCookie(new Cookie('user', $user->getId(), $expire));
    }

    /**
     * build an array to create the github request
     *
     * @param \Cypress\GitElephantHostBundle\Entity\User $user
     * @param null                                       $code
     *
     * @return array
     */
    private function buildArrayQuery(User $user, $code = null)
    {
        $clientId = $this->container->getParameter('cypress_git_elephant_host.client_id');
        $clientSecret = $this->container->getParameter('cypress_git_elephant_host.client_secret');
        $redirectUri = $this->generateUrl('github_response', array(), true);
        $state = $user->getToken();
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

    /**
     * Github access token
     *
     * @param User   $user user
     * @param string $code github code
     *
     * @return mixed
     */
    private function getAccessToken($user, $code)
    {
        $accessTokenUrl = $this->container->getParameter('cypress_git_elephant_host.access_token_url');
        $request = new BuzzRequest(BuzzRequest::METHOD_POST, '/', $accessTokenUrl);
        $request->setContent(http_build_query($this->buildArrayQuery($user, $code)));
        $response = new \Buzz\Message\Response();
        $client = new Curl();
        $client->send($request, $response);
        parse_str($response->getContent());

        return $access_token;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return array
     */
    private function getLinkHeader(Response $response)
    {
        $links = array();
        $data = $response->headers->get('link');
        foreach (explode(',', $data) as $link) {
            $matches = array();
            preg_match('/<(.*)>; rel="(.*)"/', $link, $matches);
            $links[$matches[2]] = $matches[1];
        }

        return $links;
    }
}
