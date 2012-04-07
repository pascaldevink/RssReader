<?php

namespace Pascal\FeedGathererBundle\Controller;

use \Symfony\Component\HttpFoundation\Request;

class TwitterController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
	private static $config = array(
		'consumer_key' => 'BlR9tlw0CDzFSLcMJuMhRA',
		'consumer_secret' => 'yD9DjMKXudMMWz7Yff61pFDGo0mmSZjNZzgjqOxjPQ'
	);

	public function indexAction(Request $request)
	{
		$callback = $this->generateUrl('PascalFeedGathererBundle_twitter_callback', array(), true);
		$params = array(
			'oauth_callback'	=> $callback
		);
		$params['x_auth_access_type'] = 'read';

		$twitter = $this->getTwitter();

		$code = $twitter->request('POST', $twitter->url('oauth/request_token', ''), $params);
		$response = $twitter->getResponse();
		if ($code == 200)
		{
			$oauth = $twitter->extract_params($response['response']);
			$request->getSession()->set('oauth', $oauth);

			$method = 'authorize';
			$authurl = $twitter->url("oauth/{$method}", '') .  "?oauth_token={$oauth['oauth_token']}";

			return new \Symfony\Component\HttpFoundation\Response(
				'<p>To complete the OAuth flow follow this URL: <a href="'. $authurl . '">' . $authurl . '</a></p>'
			);
		}

		var_dump($response);
		return new \Symfony\Component\HttpFoundation\Response('ERROR');
	}

	public function callbackAction(Request $request)
	{
		$twitter = $this->getTwitter();

		$oauth = $request->getSession()->get('oauth');
		$twitter->setConfigValue('user_token', $oauth['oauth_token']);
		$twitter->setConfigValue('user_secret', $oauth['oauth_token_secret']);

		$code = $twitter->request('POST', $twitter->url('oauth/access_token', ''), array(
			'oauth_verifier' => $request->get('oauth_verifier')
		));

		$response = $twitter->getResponse();
		if ($code == 200) {
			$accessTokens = $twitter->extract_params($response['response']);
			$request->getSession()->remove('oauth');

			// FIXME: Refactor this to the twitter service
			$twitterUser = new \Pascal\FeedGathererBundle\Entity\TwitterUser();
			$twitterUser->setOauthToken($accessTokens['oauth_token']);
			$twitterUser->setOauthTokenSecret($accessTokens['oauth_token_secret']);
			$twitterUser->setUserId($accessTokens['user_id']);
			$twitterUser->setScreenName($accessTokens['screen_name']);

			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($twitterUser);
			$em->flush();

			$redirect = $this->generateUrl('PascalFeedDisplayerBundle_homepage', array(), true);
			return $this->redirect($redirect);
		}

		var_dump($response);
		return new \Symfony\Component\HttpFoundation\Response('ERROR');
	}

	/**
	 * @return \tmhOAuth
	 */
	protected function getTwitter()
	{
		$twitter = $this->get('twitter.rss');
		$twitter->load(self::$config);
		return $twitter;
	}
}
