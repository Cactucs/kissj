<?php

namespace Tests\Functional;


use kissj\User\UserService;

class RegisterTest extends BaseTestCase {

	/**
	 * Test that the index route returns a rendered response containing the text 'SlimFramework' but not a greeting
	 */
	public function testRegisterAndLogin() {
		$app = $this->app();
		/** @var UserService $userService */
		$userService = $app->getContainer()->get('userService');

		$email = 'test@example.com';
		$userId = $userService->registerUser($email);
		$token = $userService->sendLoginLink($email);
		$loadedId = $userService->getUserId($token);

		$this->assertEquals($userId, $loadedId);
	}
}