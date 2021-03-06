<?php

namespace kissj\User;

use DateTime;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Created by PhpStorm.
 * User: Azathoth
 * Date: 2017-10-25
 * Time: 20:22
 */
class UserService {

	/** @var UserRepository */
	private $userRepository;

	/** @var PHPMailer */
	private $mailer;
	/** @var LoginTokenRepository */
	private $loginTokenRepository;

	public function __construct(UserRepository $userRepository, LoginTokenRepository $loginTokenRepository, PHPMailer $mailer) {
		$this->userRepository = $userRepository;
		$this->mailer = $mailer;
		$this->loginTokenRepository = $loginTokenRepository;
	}

	public function registerUser(string $email): int {
		$user = new User();
		$user->email = $email;
		$this->userRepository->persist($user);
		return $user->id;
	}

	public function sendLoginLink(string $email): string {
		$user = $this->userRepository->findOneBy(['email' => $email]);
		$loginToken = new LoginToken();
		$token = Random::generateToken();
		$loginToken->token = $token;
		$loginToken->user = $user;
		$loginToken->created = new \DateTime();
		$loginToken->used = false;
		$this->loginTokenRepository->persist($loginToken);
//		todo: poslat email s linkem
		return $token;
	}

	public function getUser(string $token): User {
		$user = $this->loginTokenRepository->findOneBy(['token' => $token]);
		return $user;
	}

}