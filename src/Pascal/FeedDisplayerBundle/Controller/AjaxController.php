<?php

namespace Pascal\FeedDisplayerBundle\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Request;
use \Pascal\FeedDisplayerBundle\Filter\LastUpdateTimeFilter;
use \Pascal\FeedDisplayerBundle\Service\FeedEntry\FeedEntryFilterSettings;

class AjaxController extends Controller
{

	/**
	 * Check if any new feed entries were added since the given lastRefreshTime parameter.
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function newEntriesCheckAction(Request $request)
	{
		$lastRefreshTime = $request->get('lastRefreshTime');
		$lastUpdateTime = new \DateTime("@" . ceil($lastRefreshTime / 1000));

		$filterSetings = new FeedEntryFilterSettings($request);
		$filterSetings->setLastUpdateTime(new LastUpdateTimeFilter($lastUpdateTime));
		$filterSetings->setPaging(false);

		$result = $this->getFeedEntryService()->getFeedEntries($filterSetings);
		return new \Symfony\Component\HttpFoundation\Response($result->getFilteredCount());
	}

	public function addTagAction(Request $request)
	{
		$tagService = $this->getTagService();
		$feedEntryService = $this->getFeedEntryService();

		$tagName = $request->get('tagName');
		$feedEntryId = $request->get('feedEntryId');

		$tag = $tagService->getTagByName($tagName);
		if (is_null($tag))
		{
			$tag = $tagService->addNewTag($tagName);
		}

		$feedEntry = $feedEntryService->getFeedEntryById($feedEntryId);
		$tagResult = $tagService->tagFeedEntry($tag, $feedEntry);

		if ($tagResult === true)
			$jsonTag = array('id' => $tag->getId(), 'name' => $tag->getName());
		else
			$jsonTag = array('error' => 'Feed entry already tagged');

		return new \Symfony\Component\HttpFoundation\Response(json_encode($jsonTag));
	}

	public function deleteTagAction(Request $request)
	{
		$tagService = $this->getTagService();
		$feedEntryService = $this->getFeedEntryService();

		$tagId = $request->get('tagId');
		$feedEntryId = $request->get('feedEntryId');

		$tag = $tagService->getTagById($tagId);
		$feedEntry = $feedEntryService->getFeedEntryById($feedEntryId);

		$tagResult = $tagService->unTagFeedEntry($tag, $feedEntry);

		if ($tagResult === true)
			$jsonTag = array('message' => 'Tag removed');
		else
			$jsonTag = array('error' => 'Feed entry not tagged');

		return new \Symfony\Component\HttpFoundation\Response(json_encode($jsonTag));
	}

	public function deleteFeedEntryAction(Request $request)
	{
		$feedEntryId = $request->get('feedEntryId');

		$feedEntryService = $this->getFeedEntryService();
		$tagResult = $feedEntryService->deleteFeedEntry($feedEntryId);

		if ($tagResult !== false)
			$jsonTag = array('message' => 'FeedEntry removed');
		else
			$jsonTag = array('error' => 'Feed entry not removed');

		return new \Symfony\Component\HttpFoundation\Response(json_encode($jsonTag));
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
}
