<?php

namespace Pascal\FeedDisplayerBundle\Command;

use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class UserCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName("user:add")
			->setDescription("Add a new user to the database")
			->setDefinition(array(
				new InputArgument(
					'email',
					InputArgument::REQUIRED,
					'The email address of the new user'
				),
				new InputArgument(
					'password',
					InputArgument::REQUIRED,
					'The password of the new user'
				)
			));
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$email = $input->getArgument('email');
		$insecurePassword = $input->getArgument('password');

		$factory = $this->getContainer()->get('security.encoder_factory');
		$user = new \Pascal\FeedDisplayerBundle\Entity\User();
		$user->setUsername($email);

		$encoder = $factory->getEncoder($user);
		$password = $encoder->encodePassword($insecurePassword, $user->getSalt());
		$user->setPassword($password);

		$doctrine = $this->getContainer()->get('doctrine');
		$entityManager = $doctrine->getEntityManager();
		$entityManager->persist($user);
		$entityManager->flush();
	}
}
