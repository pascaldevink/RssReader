<?php

namespace Pascal\FeedDisplayerBundle\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Request;
use \Pascal\FeedDisplayerBundle\Filter\LastUpdateTimeFilter;
use \Pascal\FeedDisplayerBundle\Service\FeedEntry\FeedEntryFilterSettings;

class DefaultController extends Controller
{
    
    public function indexAction(Request $request, $page = 1)
    {
		$feedEntryService = $this->getFeedEntryService();
		$filterSettings = new FeedEntryFilterSettings($request);
		$filterSettings->setPage($page);

		$entries = $feedEntryService->getFeedEntries($filterSettings);

		$startNumber = $page * 25 - 25 + 1;
		$endNumber = $page * 25 - 25 + $entries->getFilteredCount();
		$numberOfPages = ceil($entries->getTotalCount() / 25);

        return $this->render(
			'PascalFeedDisplayerBundle:Default:index.html.twig',
			array(
				'numberOfEntries'	=> $entries->getTotalCount(),
				'startNumber'		=> $startNumber,
				'endNumber'			=> $endNumber,
				'page'				=> $page,
				'numberOfPages'		=> $numberOfPages,
				'entries'			=> $entries->getItemList(),
			)
		);
    }

	public function sidebarAction(Request $request, $currentPage)
	{
		$feedHandlerService = $this->getFeedHandlerService();
		$feedTypes = array();
		foreach($feedHandlerService->getFeedHandlers() as $feedHandler)
		{
			$feedTypes[] = $feedHandler->getServiceType();
		}

		$tagService = $this->getTagService();
		$tagResult = $tagService->getTagsWithFeedEntries();
		$tags = $tagResult->getItemList();

		return $this->render(
			'PascalFeedDisplayerBundle:Default:sidebar.html.twig',
			array(
				'currentPage'	=> $currentPage,
				'feedTypes'		=> $feedTypes,
				'tags'			=> $tags,
			)
		);
	}

	/**
	 * @return \Pascal\FeedDisplayerBundle\Service\FeedEntry\FeedEntryService
	 */
	protected function getFeedEntryService()
	{
		$feedEntryService = $this->get('feedEntryService');
		return $feedEntryService;
	}

	/**
	 * @return \Pascal\FeedDisplayerBundle\Service\Tag\TagService
	 */
	protected function getTagService()
	{
		$tagService = $this->get('tagService');
		return $tagService;
	}

	/**
	 * @return \Pascal\FeedGathererBundle\Service\FeedHandlerService
	 */
	protected function getFeedHandlerService()
	{
		$feedHandlerService = $this->get('feedHandler');
		return $feedHandlerService;
	}
}
