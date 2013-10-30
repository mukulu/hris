<?php
/*
 *
 * Copyright 2012 Human Resource Information System
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 * @since 2012
 * @author John Francis Mukulu <john.f.mukulu@gmail.com>
 *
 */
namespace Hris\MessageBundle\Controller;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\MessageBundle\Provider\ProviderInterface;
use Doctrine\ORM\QueryBuilder as QueryBuilder;

/**
 * Message controller.
 *
 * @Route("/messages")
 */
class MessageController extends ContainerAware
{
    /**
     * Displays the authenticated participant inbox
     *
     * @Secure(roles="ROLE_MESSAGE_INBOX,ROLE_USER")
     * @Route("/", name="message_inbox")
     * @Method("GET")
     * @Template()
     * @return Response
     */
    public function inboxAction()
    {
        $threads = $this->getProvider()->getInboxThreads();

        return $this->container->get('templating')->renderResponse('HrisMessageBundle:Message:inbox.html.twig', array(
            'threads' => $threads
        ));
    }

    /**
     * Displays the authenticated participant sent mails
     *
     * @Secure(roles="ROLE_MESSAGE_SENT,ROLE_USER")
     * @Route("/sent", name="message_sent")
     * @Method("GET")
     * @Template()
     * @return Response
     */
    public function sentAction()
    {
        $threads = $this->getProvider()->getSentThreads();

        return $this->container->get('templating')->renderResponse('HrisMessageBundle:Message:sent.html.twig', array(
            'threads' => $threads
        ));
    }

    /**
     * Displays a thread, also allows to reply to it
     *
     * @Secure(roles="ROLE_MESSAGE_THREAD,ROLE_USER")
     * @Route("/{threadId}", requirements={"threadId"="\d+"}, name="message_thread_view")
     * @Method("GET|POST")
     * @Template()
     * @param string $threadId the thread id
     * @return Response
     */
    public function threadAction($threadId)
    {
        $thread = $this->getProvider()->getThread($threadId);
        $form = $this->container->get('fos_message.reply_form.factory')->create($thread);
        $formHandler = $this->container->get('fos_message.reply_form.handler');

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('message_thread_view', array(
                'threadId' => $message->getThread()->getId()
            )));
        }

        return $this->container->get('templating')->renderResponse('HrisMessageBundle:Message:thread.html.twig', array(
            'form' => $form->createView(),
            'thread' => $thread
        ));
    }

    /**
     * Create a new message thread
     *
     * @Secure(roles="ROLE_MESSAGE_CREATETHREAD,ROLE_USER")
     * @Route("/new", name="message_thread_new")
     * @Method("GET|POST")
     * @Template()
     * @return Response
     */
    public function newThreadAction()
    {
        $form = $this->container->get('fos_message.new_thread_form.factory')->create();
        $formHandler = $this->container->get('fos_message.new_thread_form.handler');

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('message_thread_view', array(
                'threadId' => $message->getThread()->getId()
            )));
        }

        return $this->container->get('templating')->renderResponse('HrisMessageBundle:Message:newThread.html.twig', array(
            'form' => $form->createView(),
            'data' => $form->getData()
        ));
    }

    /**
     * Create a new multi message thread
     *
     * @Secure(roles="ROLE_MESSAGE_MULTIMESSAGECREATETHREAD,ROLE_USER")
     * @Route("/new/multimessage", name="multi_message_thread_new")
     * @Method("GET|POST")
     * @Template()
     * @return Response
     */
    public function newMultiMessageThreadAction()
    {
        $form = $this->container->get('fos_message.new_thread_form.factory')->create();
        $formHandler = $this->container->get('fos_message.new_thread_form.handler');

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('message_thread_view', array(
                'threadId' => $message->getThread()->getId()
            )));
        }

        return $this->container->get('templating')->renderResponse('HrisMessageBundle:Message:newThread.html.twig', array(
            'form' => $form->createView(),
            'data' => $form->getData()
        ));
    }
    /**
     * Returns Users searched json.
     *
     * @Secure(roles="ROLE_MESSAGE_SEARCHUSERS,ROLE_USER")
     * @Route("/searchusers",  name="search_users")
     * @Method("GET")
     * @Template()
     */
    public function searchUsersAction(Request $request)
    {

        $q = $request->query->get('q');
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        /*
         * Getting the Users
        */
        $users = $entityManager->getRepository('HrisUserBundle:User')->getSearchedUsers($q);

        /*
         * Getting the Users Groups

        $userGroups = $entityManager->getRepository('HrisUserBundle:Group')->getSearchedUserGroups($q);
        */

        //Add the User to the json response
        foreach($users as $user){
            $arr[] = Array('id'=>$user->getUsername(),'name'=>$user->getFirstName().' '.$user->getSurname(),"url"=>$this->container->get('templating.helper.assets')->getUrl("commons/images/user.png"));
        }
        /*
        * Add the User groups to the json response
       foreach($userGroups as $userGroup){
           $arr[] = Array('id'=>$userGroup->getId(),'name'=>$userGroup->getName(),"url"=>$this->container->get('templating.helper.assets')->getUrl("commons/images/user.png"));
       }*/


        # JSON-encode the response
        if(empty($arr)) $arr= NULL;
        $json_response = json_encode($arr);

        return $this->container->get('templating')->renderResponse('HrisMessageBundle:Message:searchUsers.html.twig', array(
            'searchUsers' => $json_response
        ));
    }

    /**
     * Deletes a thread
     *
     * @Secure(roles="ROLE_MESSAGE_DELETE,ROLE_USER")
     * @Route("/{threadId}/delete", requirements={"threadId"="\d+"}, name="message_thread_delete")
     * @Method("POST|DELETE")
     * @Template()
     * @param string $threadId the thread id
     * @return Response
     */
    public function deleteAction($threadId)
    {
        $thread = $this->getProvider()->getThread($threadId);
        $this->container->get('fos_message.deleter')->markAsDeleted($thread);
        $this->container->get('fos_message.thread_manager')->saveThread($thread);

        return new RedirectResponse($this->container->get('router')->generate('message_inbox'));
    }

    /**
     * Searches for messages in the inbox and sentbox
     *
     * @Secure(roles="ROLE_MESSAGE_SEARCH,ROLE_USER")
     * @Route("/search", name="message_search")
     * @Method("GET")
     * @Template()
     * @return Response
     */
    public function searchAction()
    {
        $query = $this->container->get('fos_message.search_query_factory')->createFromRequest();
        $threads = $this->container->get('fos_message.search_finder')->find($query);

        return $this->container->get('templating')->renderResponse('HrisMessageBundle:Message:search.html.twig', array(
            'query' => $query,
            'threads' => $threads
        ));
    }

    /**
     * Gets the provider service
     *
     * @return ProviderInterface
     */
    protected function getProvider()
    {
        return $this->container->get('fos_message.provider');
    }
}
