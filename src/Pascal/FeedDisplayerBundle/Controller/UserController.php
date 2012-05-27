<?php

namespace Pascal\FeedDisplayerBundle\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class UserController extends Controller
{

	public function loginAction(Request $request)
	{
		$session = $request->getSession();

		if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
			$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
		} else {
			$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
			$session->remove(SecurityContext::AUTHENTICATION_ERROR);
		}

		return $this->render('PascalFeedDisplayerBundle:User:login.html.twig', array (
			// last username entered by the user
			'last_username'	=> $session->get(SecurityContext::LAST_USERNAME),
			'error'			=> $error,
		));
	}

	public function settingsAction(Request $request)
	{
		$serviceType = $request->get('serviceType');
		$feedSettingsHandlerService = $this->getFeedSettingsHandlerService();
		$feedSettingsHandlers = $feedSettingsHandlerService->getFeedSettingsHandlers();

		$currentFeedSettingsHandler = null;
		if ($serviceType != 'none')
		{
			foreach($feedSettingsHandlers as $feedSettingsHandler)
			{
				if ($feedSettingsHandler->getServiceType() == $serviceType)
					$currentFeedSettingsHandler = $feedSettingsHandler;
			}
		}

//		$twitterFeedService = $this->getTwitterFeedService();
//
//		$feed = new \Pascal\FeedGathererBundle\Entity\Feed();
//		$feed->setTypeId(1);
//		$twitterUser = $twitterFeedService->getTwitterUser($feed);
//		$blacklistUsernames = $twitterUser->getBlacklistUsernames();

//		return $this->render('PascalFeedDisplayerBundle:User:settings.html.twig', array (
//			'blacklistUsernames' => $blacklistUsernames
//		));
	}

	public function accountAction(Request $request)
	{

	}

	/**
	 * @return \Pascal\FeedDisplayerBundle\Service\FeedSettingsHandlerService
	 */
	protected function getFeedSettingsHandlerService()
	{
		return $this->get('feedSettingsHandler');
	}
}
