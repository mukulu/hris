<?php

namespace Udsm\Hrhis\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * @Route("/secured")
 */
class SecuredController extends Controller
{
    /**
     * @Route("/login", name="_hrhis_login")
     * @Template()
     */
    public function loginAction()
    {
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            'last_username' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        );
    }

    /**
     * @Route("/login_check", name="_hrhis_security_check")
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/logout", name="_hrhis_logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/dashboard", defaults={"name"="Anonymous"}),
     * @Route("/dashboard/{name}", name="_hrhis_secured_dashboard")
     * @Template()
     */
    public function dashboardAction($name)
    {
        return array('name' => $name);
    }

    /**
     * @Route("/dashboard/admin/{name}", name="_hrhis_secured_dashboard_admin")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function dashboardAdminAction($name)
    {
        return array('name' => $name);
    }
}
