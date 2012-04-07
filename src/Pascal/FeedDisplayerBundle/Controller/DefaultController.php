<?php

namespace Pascal\FeedDisplayerBundle\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    
    public function indexAction(Request $request, $page = 1)
    {
		$feedEntryService = $this->getFeedEntryService();

		$numberOfEntries = $feedEntryService->getNumberOfFeedEntries();
		$entries = $feedEntryService->getAllFeedEntries($page);

		$startNumber = $page * 25 - 25 + 1;
		$endNumber = $page * 25 - 25 + count($entries);
		$numberOfPages = ceil($numberOfEntries / 25);

        return $this->render(
			'PascalFeedDisplayerBundle:Default:index.html.twig',
			array(
				'numberOfEntries'	=> $numberOfEntries,
				'startNumber'		=> $startNumber,
				'endNumber'			=> $endNumber,
				'page'				=> $page,
				'numberOfPages'		=> $numberOfPages,
				'entries'			=> $entries,
			)
		);
    }

	/**
	 * @return \Pascal\FeedDisplayerBundle\Service\FeedEntryService
	 */
	protected function getFeedEntryService()
	{
		$feedEntryService = $this->get('feedEntryService');
		return $feedEntryService;
	}

}
