<?php

namespace Pascal\FeedDisplayerBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

class User implements UserInterface, \Serializable
{

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $username;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $password;

	/**
	 * Returns the roles granted to the user.
	 *
	 * <code>
	 * public function getRoles()
	 * {
	 *	 return array('ROLE_USER');
	 * }
	 * </code>
	 *
	 * Alternatively, the roles might be stored on a ``roles`` property,
	 * and populated in any number of different ways when the user object
	 * is created.
	 *
	 * @return Role[] The user roles
	 */
	function getRoles()
	{
		// TODO: Implement getRoles() method.
	}

	/**
	 * Returns the password used to authenticate the user.
	 *
	 * This should be the encoded password. On authentication, a plain-text
	 * password will be salted, encoded, and then compared to this value.
	 *
	 * @return string The password
	 */
	function getPassword()
	{
		// TODO: Implement getPassword() method.
	}

	/**
	 * Returns the salt that was originally used to encode the password.
	 *
	 * This can return null if the password was not encoded using a salt.
	 *
	 * @return string The salt
	 */
	function getSalt()
	{
		// TODO: Implement getSalt() method.
	}

	/**
	 * Returns the username used to authenticate the user.
	 *
	 * @return string The username
	 */
	function getUsername()
	{
		// TODO: Implement getUsername() method.
	}

	/**
	 * Removes sensitive data from the user.
	 *
	 * This is important if, at any given point, sensitive information like
	 * the plain-text password is stored on this object.
	 *
	 * @return void
	 */
	function eraseCredentials()
	{
		// TODO: Implement eraseCredentials() method.
	}

	/**
	 * Returns whether or not the given user is equivalent to *this* user.
	 *
	 * The equality comparison should neither be done by referential equality
	 * nor by comparing identities (i.e. getId() === getId()).
	 *
	 * However, you do not need to compare every attribute, but only those that
	 * are relevant for assessing whether re-authentication is required.
	 *
	 * @param UserInterface $user
	 *
	 * @return Boolean
	 */
	function equals(UserInterface $user)
	{
		// TODO: Implement equals() method.
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * String representation of object
	 * @link http://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or &null;
	 */
	public function serialize()
	{
		// TODO: Implement serialize() method.
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Constructs the object
	 * @link http://php.net/manual/en/serializable.unserialize.php
	 * @param string $serialized <p>
	 * The string representation of the object.
	 * </p>
	 * @return mixed the original value unserialized.
	 */
	public function unserialize($serialized)
	{
		// TODO: Implement unserialize() method.
	}
}
